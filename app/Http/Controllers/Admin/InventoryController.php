<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Booking;
use Illuminate\Http\Request;
use Carbon\Carbon;

class InventoryController extends Controller
{
    /**
     * Tampilkan Laporan Real-Time Ketersediaan & Stok Kamar (Room Inventory & Occupancy)
     */
    public function index(Request $request)
    {
        $date = $request->get('date', now()->format('Y-m-d'));
        $nextDate = Carbon::parse($date)->addDay()->format('Y-m-d');

        $rooms = Room::with('images')->get();

        $inventoryData = [];
        $totalHotelStock = 0;
        $totalHotelBooked = 0;

        foreach ($rooms as $room) {
            $total = (int) ($room->total_rooms ?: 20);
            $booked = $room->getActiveBookingsCount($date, $nextDate);
            $avail = max(0, $total - $booked);

            $activeBookings = $room->getActiveBookingsList($date, $nextDate);

            $inventoryData[] = (object) [
                'room' => $room,
                'total_stock' => $total,
                'booked_stock' => $booked,
                'available_stock' => $avail,
                'occupancy_rate' => $total > 0 ? round(($booked / $total) * 100) : 0,
                'active_bookings' => $activeBookings,
            ];

            $totalHotelStock += $total;
            $totalHotelBooked += $booked;
        }

        $overallOccupancy = $totalHotelStock > 0 ? round(($totalHotelBooked / $totalHotelStock) * 100) : 0;

        return view('admin.inventory.index', compact(
            'date',
            'nextDate',
            'inventoryData',
            'totalHotelStock',
            'totalHotelBooked',
            'overallOccupancy'
        ));
    }

    public function quickUpdate(Request $request)
    {
        $request->validate([
            'stocks' => 'required|array',
            'floors' => 'nullable|array',
        ]);

        foreach ($request->stocks as $roomId => $stock) {
            $room = Room::find($roomId);
            if ($room) {
                $room->total_rooms = max(1, (int) $stock);
                if (isset($request->floors[$roomId])) {
                    $room->floor_location = $request->floors[$roomId];
                }
                $room->save();
            }
        }

        return redirect()->back()->with('success', 'Stok kamar dan lokasi lantai seluruh tipe kamar berhasil diperbarui!');
    }
}

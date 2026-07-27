<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use App\Models\HomeFacility;
use App\Models\RoomFacility;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBookings   = Booking::count();
        $totalRevenue    = Booking::where('payment_status', '!=', 'cancelled')->sum('total_price');
        $totalRooms      = Room::count();
        $totalFacilities = HomeFacility::count() + RoomFacility::count();

        $recentBookings  = Booking::with('room')
                            ->orderBy('id', 'desc')
                            ->limit(10)
                            ->get();

        $rooms = Room::with('images')->get();

        return view('admin.dashboard', compact(
            'totalBookings', 'totalRevenue', 'totalRooms', 'totalFacilities', 'recentBookings', 'rooms'
        ));
    }
}

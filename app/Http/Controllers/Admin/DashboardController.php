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

        // Calculate Monthly Revenue for the current year
        $currentYear = date('Y');
        $monthlyData = Booking::where('payment_status', '!=', 'cancelled')
            ->whereYear('created_at', $currentYear)
            ->selectRaw('MONTH(created_at) as month, SUM(total_price) as revenue')
            ->groupBy('month')
            ->pluck('revenue', 'month')->toArray();

        $monthlyLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $monthlyRevenue = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyRevenue[] = $monthlyData[$i] ?? 0;
        }

        $rooms = Room::with('images')->get();

        return view('admin.dashboard', compact(
            'totalBookings', 'totalRevenue', 'totalRooms', 'totalFacilities', 'recentBookings', 'rooms',
            'monthlyLabels', 'monthlyRevenue'
        ));
    }
}

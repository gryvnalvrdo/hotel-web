<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with('room')->orderBy('id', 'desc');

        if ($request->has('status') && $request->status !== '') {
            $query->where('payment_status', $request->status);
        }

        $bookings = $query->paginate(15);

        return view('admin.bookings.index', compact('bookings'));
    }

    public function show($id)
    {
        $booking = Booking::with('room')->findOrFail($id);
        return view('admin.bookings.show', compact('booking'));
    }

    public function updateStatus(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        $request->validate([
            'payment_status' => 'required|in:unpaid,paid,pay_at_hotel,cancelled',
        ]);

        $booking->update(['payment_status' => $request->payment_status]);

        return redirect()->back()->with('success', 'Status reservasi berhasil diperbarui menjadi: ' . strtoupper($request->payment_status));
    }

    public function destroy($id)
    {
        Booking::findOrFail($id)->delete();
        return redirect()->route('admin.bookings.index')->with('success', 'Data reservasi dihapus dari sistem.');
    }
}

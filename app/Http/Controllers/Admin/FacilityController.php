<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeFacility;
use App\Models\RoomFacility;
use App\Models\Room;
use Illuminate\Http\Request;

class FacilityController extends Controller
{
    public function index()
    {
        $homeFacilities = HomeFacility::with('images')->orderBy('id')->get();
        $roomFacilities = RoomFacility::with('room')->orderBy('id', 'desc')->get();
        $rooms          = Room::orderBy('name')->get();

        return view('admin.facilities.index', compact('homeFacilities', 'roomFacilities', 'rooms'));
    }

    public function updateHome(Request $request, $id)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $hf = HomeFacility::findOrFail($id);
        $hf->update([
            'title'       => $request->title,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.facilities.index')->with('success', 'Fasilitas umum hotel berhasil diperbarui.');
    }

    public function updateRoom(Request $request, $id)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'icon'     => 'required|string|max:100',
            'category' => 'required|string|max:50',
        ]);

        $rf = RoomFacility::findOrFail($id);
        $rf->update([
            'facility_name' => $request->name,
            'icon'          => $request->icon ?? 'bi bi-check2-circle',
            'category'      => $request->category,
        ]);

        return redirect()->route('admin.facilities.index')->with('success', 'Fasilitas kamar berhasil diperbarui.');
    }

    public function storeHome(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'icon'        => 'nullable|string|max:100',
        ]);

        HomeFacility::create([
            'title'       => $request->title,
            'description' => $request->description,
            'icon'        => $request->icon ?? 'bi bi-stars',
        ]);

        return redirect()->route('admin.facilities.index')->with('success', 'Fasilitas umum hotel berhasil ditambahkan.');
    }

    public function storeRoom(Request $request)
    {
        $request->validate([
            'room_id'  => 'required|exists:rooms,id',
            'name'     => 'required|string|max:255',
            'icon'     => 'required|string|max:100',
            'category' => 'required|string|max:50',
        ]);

        RoomFacility::create([
            'room_id'       => $request->room_id,
            'facility_name' => $request->name,
            'icon'          => $request->icon ?? 'bi bi-check2-circle',
            'category'      => $request->category,
        ]);

        return redirect()->route('admin.facilities.index')->with('success', 'Fasilitas kamar berhasil ditambahkan.');
    }

    public function destroyHome($id)
    {
        HomeFacility::findOrFail($id)->delete();
        return redirect()->route('admin.facilities.index')->with('success', 'Fasilitas umum hotel dihapus.');
    }

    public function destroyRoom($id)
    {
        RoomFacility::findOrFail($id)->delete();
        return redirect()->route('admin.facilities.index')->with('success', 'Fasilitas kamar dihapus.');
    }
}

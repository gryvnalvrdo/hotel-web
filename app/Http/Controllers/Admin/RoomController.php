<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\RoomImage;
use App\Models\RoomFacility;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::with(['images', 'facilities'])->orderBy('id')->get();
        return view('admin.rooms.index', compact('rooms'));
    }

    public function create()
    {
        return view('admin.rooms.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'              => 'required|string|max:255',
            'price'             => 'required|numeric|min:0',
            'capacity'          => 'required|integer|min:1',
            'total_rooms'       => 'nullable|integer|min:1',
            'floor_location'    => 'nullable|string',
            'short_description' => 'nullable|string',
            'full_description'  => 'nullable|string',
            'image'             => 'nullable|image|max:2048',
        ]);

        $room = Room::create([
            'name'              => $request->name,
            'price'             => $request->price,
            'capacity'          => $request->capacity,
            'total_rooms'       => $request->total_rooms ?? 20,
            'floor_location'    => $request->floor_location ?? 'Lantai 3 - 8 (Tower Utama)',
            'short_description' => $request->short_description ?? 'Kamar mewah dengan kenyamanan eksklusif dan pemandangan kota Makassar.',
            'full_description'  => $request->full_description ?? 'Dilengkapi tempat tidur bermerek, kamar mandi marmer, fasilitas sarapan gratis, dan akses internet berkecepatan tinggi.',
        ]);

        if ($request->hasFile('image')) {
            $file     = $request->file('image');
            $filename = 'room_' . time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/rooms'), $filename);

            RoomImage::create([
                'room_id'       => $room->id,
                'file_path'     => 'images/rooms/' . $filename,
                'caption'       => $room->name,
                'is_primary'    => true,
                'display_order' => 1,
            ]);
        } else {
            // Default image
            RoomImage::create([
                'room_id'       => $room->id,
                'file_path'     => 'images/slider/slider1.jpg',
                'caption'       => $room->name,
                'is_primary'    => true,
                'display_order' => 1,
            ]);
        }

        // Add default facilities
        $defaultFacilities = [
            ['name' => 'Free Wi-Fi Kecepatan Tinggi', 'icon' => 'bi bi-wifi', 'category' => 'utama'],
            ['name' => 'Sarapan Prasmanan Gratis', 'icon' => 'bi bi-cup-hot-fill', 'category' => 'utama'],
            ['name' => 'Smart LED TV 50-inch', 'icon' => 'bi bi-tv', 'category' => 'kamar'],
            ['name' => 'Kamar Mandi Marmer & Shower', 'icon' => 'bi bi-water', 'category' => 'kamar'],
        ];

        foreach ($defaultFacilities as $f) {
            RoomFacility::create([
                'room_id'  => $room->id,
                'name'     => $f['name'],
                'icon'     => $f['icon'],
                'category' => $f['category'],
            ]);
        }

        return redirect()->route('admin.rooms.index')->with('success', 'Kamar baru berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $room = Room::with(['images', 'facilities'])->findOrFail($id);
        return view('admin.rooms.edit', compact('room'));
    }

    public function update(Request $request, $id)
    {
        $room = Room::findOrFail($id);

        $request->validate([
            'name'              => 'required|string|max:255',
            'price'             => 'required|numeric|min:0',
            'capacity'          => 'required|integer|min:1',
            'total_rooms'       => 'nullable|integer|min:1',
            'floor_location'    => 'nullable|string',
            'short_description' => 'nullable|string',
            'full_description'  => 'nullable|string',
            'image'             => 'nullable|image|max:2048',
        ]);

        $room->update([
            'name'              => $request->name,
            'price'             => $request->price,
            'capacity'          => $request->capacity,
            'total_rooms'       => $request->total_rooms ?? $room->total_rooms,
            'floor_location'    => $request->floor_location ?? $room->floor_location,
            'short_description' => $request->short_description,
            'full_description'  => $request->full_description,
        ]);

        if ($request->hasFile('image')) {
            $file     = $request->file('image');
            $filename = 'room_' . time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/rooms'), $filename);

            $primaryImg = $room->images()->where('is_primary', true)->first();
            if ($primaryImg) {
                $primaryImg->update(['file_path' => 'images/rooms/' . $filename]);
            } else {
                RoomImage::create([
                    'room_id'       => $room->id,
                    'file_path'     => 'images/rooms/' . $filename,
                    'caption'       => $room->name,
                    'is_primary'    => true,
                    'display_order' => 1,
                ]);
            }
        }

        return redirect()->route('admin.rooms.index')->with('success', 'Data kamar berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $room = Room::findOrFail($id);
        $room->images()->delete();
        $room->facilities()->delete();
        $room->delete();

        return redirect()->route('admin.rooms.index')->with('success', 'Kamar berhasil dihapus.');
    }
}

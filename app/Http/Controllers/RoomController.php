<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\FooterBranding;
use App\Models\FooterSocial;
use App\Models\FooterPartner;
use App\Models\FooterContact;
use App\Models\FooterBottom;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::with(['images', 'facilities'])->get()->map(function ($room) {
            if (!$room->price || $room->price <= 0) {
                if (stripos($room->name, 'presidential') !== false) {
                    $room->price = 7500000;
                } elseif (stripos($room->name, 'executive') !== false || stripos($room->name, 'family') !== false) {
                    $room->price = 3200000;
                } elseif (stripos($room->name, 'suite') !== false) {
                    $room->price = 2200000;
                } elseif (stripos($room->name, 'superior') !== false) {
                    $room->price = 1200000;
                } else {
                    $room->price = 1500000;
                }
                $room->save();
            }

            $room->facilitiesByCategory = $room->facilities->groupBy('category');
            return $room;
        });

        $branding     = FooterBranding::first();
        $socials      = FooterSocial::orderBy('display_order')->get();
        $partners     = FooterPartner::orderBy('display_order')->get();
        $contacts     = FooterContact::orderBy('display_order')->get();
        $footerBottom = FooterBottom::first();

        return view('rooms.index', compact(
            'rooms', 'branding', 'socials', 'partners', 'contacts', 'footerBottom'
        ));
    }
}

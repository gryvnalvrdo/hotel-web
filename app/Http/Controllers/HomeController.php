<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\HomeSlider;
use App\Models\HomeFacility;
use App\Models\FooterBranding;
use App\Models\FooterSocial;
use App\Models\FooterPartner;
use App\Models\FooterContact;
use App\Models\FooterBottom;

class HomeController extends Controller
{
    public function index()
    {
        $heroSlides  = HomeSlider::orderBy('id')->get();
        $rooms       = Room::with(['images'])->get()->map(function ($room) {
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

            $room->thumbnail   = $room->images->first()?->file_path;
            $room->mainFacilities = $room->facilities()->where('category', 'utama')->limit(2)->get();
            $room->roomFacilities = $room->facilities()->where('category', 'kamar')->limit(4)->get();
            return $room;
        });
        $facilities  = HomeFacility::with('images')
            ->select('id', 'title', 'description')
            ->orderBy('id')
            ->limit(10)
            ->get()
            ->unique('title');
        $branding    = FooterBranding::first();
        $socials     = FooterSocial::orderBy('display_order')->get();
        $partners    = FooterPartner::orderBy('display_order')->get();
        $contacts    = FooterContact::orderBy('display_order')->get();
        $footerBottom = FooterBottom::first();

        return view('home.index', compact(
            'heroSlides', 'rooms', 'facilities',
            'branding', 'socials', 'partners', 'contacts', 'footerBottom'
        ));
    }
}

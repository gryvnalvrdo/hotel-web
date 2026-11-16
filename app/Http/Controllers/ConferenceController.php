<?php

namespace App\Http\Controllers;

use App\Services\HotelShowcaseService;
use App\Models\FooterBranding;
use App\Models\FooterSocial;
use App\Models\FooterPartner;
use App\Models\FooterContact;
use App\Models\FooterBottom;

class ConferenceController extends Controller
{
    public function index()
    {
        $conferenceRooms = HotelShowcaseService::resolveConferenceRooms();

        $branding     = FooterBranding::first();
        $socials      = FooterSocial::orderBy('display_order')->get();
        $partners     = FooterPartner::orderBy('display_order')->get();
        $contacts     = FooterContact::orderBy('display_order')->get();
        $footerBottom = FooterBottom::first();

        return view('conference.index', compact(
            'conferenceRooms', 'branding', 'socials', 'partners', 'contacts', 'footerBottom'
        ));
    }
}

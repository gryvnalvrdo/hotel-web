<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\ConferenceRoom;
use App\Models\ConferenceRoomImage;
use App\Models\Room;
use App\Models\RoomImage;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class HotelShowcaseService
{
    /**
     * Resolve conference halls and guarantee every room renders valid images
     * (combining local assets in public/images/conferences and curated web photos).
     */
    public static function resolveConferenceRooms(): Collection
    {
        $catalogDefaults = [
            0 => [
                'images/conferences/sandeq1.jpg',
                'images/conferences/sandeq2.jpg',
                'https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=1200&q=80',
            ],
            1 => [
                'images/conferences/jasmine1.jpg',
                'images/conferences/jasmine2.jpg',
                'https://images.unsplash.com/photo-1505373877841-8d25f7d46678?auto=format&fit=crop&w=1200&q=80',
            ],
            2 => [
                'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=1200&q=80',
                'https://images.unsplash.com/photo-1582653291997-079a1c04e5a1?auto=format&fit=crop&w=1200&q=80',
            ],
        ];

        try {
            $rooms = ConferenceRoom::with('images')->orderBy('id')->get();

            if ($rooms->isEmpty()) {
                $seedRooms = [
                    [
                        'name' => 'Sandeq Grand Ballroom',
                        'description' => 'Grand column-free ballroom ideal for national conferences, gala dinners, and corporate conventions in Makassar.',
                        'width' => 32,
                        'length' => 48,
                        'capacity' => 1200,
                    ],
                    [
                        'name' => 'Jasmine Meeting Hall',
                        'description' => 'Flexible medium hall equipped with dual projectors, acoustic panels, and modular theater or classroom seating.',
                        'width' => 16,
                        'length' => 22,
                        'capacity' => 180,
                    ],
                    [
                        'name' => 'Phinisi Executive Boardroom',
                        'description' => 'Private boardroom suite designed for executive briefings, hybrid video meetings, and diplomatic discussions.',
                        'width' => 10,
                        'length' => 14,
                        'capacity' => 30,
                    ],
                ];

                foreach ($seedRooms as $idx => $data) {
                    $room = ConferenceRoom::create($data);
                    foreach ($catalogDefaults[$idx] as $order => $imgPath) {
                        ConferenceRoomImage::create([
                            'room_id' => $room->id,
                            'image_path' => $imgPath,
                            'display_order' => $order + 1,
                        ]);
                    }
                }

                $rooms = ConferenceRoom::with('images')->orderBy('id')->get();
            }

            foreach ($rooms as $idx => $room) {
                $fallbackSet = $catalogDefaults[$idx % count($catalogDefaults)];
                $hasBrokenOrEmpty = $room->images->isEmpty();

                foreach ($room->images as $imgIdx => $img) {
                    $path = trim((string) $img->image_path);
                    $isRemote = Str::startsWith($path, ['http://', 'https://']);
                    $isBrokenLocal = !$isRemote && ($path === '' || Str::startsWith($path, 'img/') || !file_exists(public_path($path)));

                    if ($isBrokenLocal) {
                        $replacement = $fallbackSet[$imgIdx % count($fallbackSet)];
                        $img->image_path = $replacement;
                        $hasBrokenOrEmpty = true;
                        try {
                            ConferenceRoomImage::where('id', $img->id)->update(['image_path' => $replacement]);
                        } catch (\Throwable $e) {
                        }
                    }
                }

                if ($room->images->isEmpty()) {
                    $synthetic = new Collection();
                    foreach ($fallbackSet as $order => $path) {
                        $rec = new ConferenceRoomImage([
                            'room_id' => $room->id,
                            'image_path' => $path,
                            'display_order' => $order + 1,
                        ]);
                        try {
                            $rec->save();
                        } catch (\Throwable $e) {
                        }
                        $synthetic->push($rec);
                    }
                    $room->setRelation('images', $synthetic);
                } elseif ($hasBrokenOrEmpty && $room->images->count() === 1) {
                    $extraPath = $fallbackSet[1] ?? $fallbackSet[0];
                    $extra = new ConferenceRoomImage([
                        'room_id' => $room->id,
                        'image_path' => $extraPath,
                        'display_order' => 2,
                    ]);
                    try {
                        $extra->save();
                    } catch (\Throwable $e) {
                    }
                    $room->images->push($extra);
                }
            }

            return $rooms;
        } catch (\Throwable $e) {
            return collect([
                (object) [
                    'id' => 1,
                    'name' => 'Sandeq Grand Ballroom',
                    'description' => 'Grand column-free ballroom ideal for conferences, conventions, and formal banquets.',
                    'width' => 32,
                    'length' => 48,
                    'capacity' => 1200,
                    'images' => collect(array_map(fn ($p) => (object) ['image_path' => $p], $catalogDefaults[0])),
                ],
                (object) [
                    'id' => 2,
                    'name' => 'Jasmine Meeting Hall',
                    'description' => 'Versatile event hall equipped with modern audiovisual presentation hardware.',
                    'width' => 16,
                    'length' => 22,
                    'capacity' => 180,
                    'images' => collect(array_map(fn ($p) => (object) ['image_path' => $p], $catalogDefaults[1])),
                ],
                (object) [
                    'id' => 3,
                    'name' => 'Phinisi Executive Boardroom',
                    'description' => 'Private boardroom suite for executive meetings and hybrid business sessions.',
                    'width' => 10,
                    'length' => 14,
                    'capacity' => 30,
                    'images' => collect(array_map(fn ($p) => (object) ['image_path' => $p], $catalogDefaults[2])),
                ],
            ]);
        }
    }

    /**
     * Populate realistic hotel showcase reservations if the database has fewer than 3 bookings
     * so Admin Dashboard charts, Reservations, and Occupancy Inventory look complete.
     */
    public static function ensureSampleAdminData(): void
    {
        try {
            $rooms = Room::orderBy('id')->get();
            if ($rooms->isEmpty()) {
                $defaultRooms = [
                    ['name' => 'Deluxe Room', 'short_description' => 'Kenyamanan standar premium dengan pemandangan kota Makassar.', 'capacity' => 2, 'price' => 850000, 'total_rooms' => 30, 'floor_location' => 'Lantai 3 - 5 (Tower Utama)'],
                    ['name' => 'Junior Suite', 'short_description' => 'Ruangan luas dengan area bersantai terpisah.', 'capacity' => 2, 'price' => 1250000, 'total_rooms' => 15, 'floor_location' => 'Lantai 6 - 7 (Tower Utama)'],
                    ['name' => 'Executive Suite', 'short_description' => 'Akses lounge eksekutif dengan ruang tamu privat.', 'capacity' => 2, 'price' => 1800000, 'total_rooms' => 10, 'floor_location' => 'Lantai 8 - 9 (Executive Wing)'],
                    ['name' => 'Family Room', 'short_description' => 'Kamar keluarga yang lapang dengan dua tempat tidur besar.', 'capacity' => 4, 'price' => 2200000, 'total_rooms' => 5, 'floor_location' => 'Lantai 4 (Family Wing)'],
                    ['name' => 'Presidential Suite', 'short_description' => 'Suite VIP dengan ruang makan dan pemandangan panorama kota.', 'capacity' => 4, 'price' => 5000000, 'total_rooms' => 2, 'floor_location' => 'Lantai 10 (Penthouse)'],
                ];
                $roomThumbs = [
                    'images/rooms/deluxe1.jpg',
                    'images/rooms/junior1.jpg',
                    'images/rooms/executive1.jpg',
                    'images/rooms/deluxe2.jpg',
                    'images/rooms/executive2.jpg',
                ];
                foreach ($defaultRooms as $i => $rData) {
                    $room = Room::create($rData);
                    RoomImage::create(['room_id' => $room->id, 'file_path' => $roomThumbs[$i]]);
                }
                $rooms = Room::orderBy('id')->get();
            }

            if ($rooms->isEmpty() || Booking::count() >= 3) {
                return;
            }

            $today = Carbon::today();
            $yearStart = Carbon::now()->startOfYear();
            $currentMonth = (int) Carbon::now()->month;

            $pickRoom = fn (int $idx) => $rooms->get($idx % $rooms->count());

            $samples = [
                [
                    'order_id' => 'CLARO-DEMO-101',
                    'room' => $pickRoom(0),
                    'name' => 'Andi Pratama',
                    'email' => 'andi.pratama@example.com',
                    'phone' => '+62 812-4401-1122',
                    'checkin' => $today->copy()->subDay()->format('Y-m-d'),
                    'checkout' => $today->copy()->addDays(2)->format('Y-m-d'),
                    'nights' => 3,
                    'room_count' => 2,
                    'guests' => 4,
                    'status' => 'confirmed',
                    'payment_status' => 'paid',
                    'notes' => 'Late check-in pukul 20.00 WITA, minta lantai tenang.',
                    'created_at' => Carbon::now()->subDays(1),
                ],
                [
                    'order_id' => 'CLARO-DEMO-102',
                    'room' => $pickRoom(1),
                    'name' => 'Siti Rahmawati',
                    'email' => 'siti.rahmawati@example.com',
                    'phone' => '+62 813-5520-8810',
                    'checkin' => $today->copy()->format('Y-m-d'),
                    'checkout' => $today->copy()->addDays(2)->format('Y-m-d'),
                    'nights' => 2,
                    'room_count' => 1,
                    'guests' => 2,
                    'status' => 'confirmed',
                    'payment_status' => 'paid',
                    'notes' => 'Perjalanan dinas Makassar.',
                    'created_at' => Carbon::now()->subDays(3),
                ],
                [
                    'order_id' => 'CLARO-DEMO-103',
                    'room' => $pickRoom(2),
                    'name' => 'Budi Santoso',
                    'email' => 'budi.santoso@example.com',
                    'phone' => '+62 811-9034-7721',
                    'checkin' => $today->copy()->format('Y-m-d'),
                    'checkout' => $today->copy()->addDays(3)->format('Y-m-d'),
                    'nights' => 3,
                    'room_count' => 2,
                    'guests' => 3,
                    'status' => 'pending',
                    'payment_status' => 'unpaid',
                    'notes' => 'Konfirmasi antar-jemput bandara Sultan Hasanuddin.',
                    'created_at' => Carbon::now()->subHours(6),
                ],
                [
                    'order_id' => 'CLARO-DEMO-104',
                    'room' => $pickRoom(3),
                    'name' => 'Clarissa Tan',
                    'email' => 'clarissa.tan@example.com',
                    'phone' => '+62 818-7712-3044',
                    'checkin' => $today->copy()->addDays(4)->format('Y-m-d'),
                    'checkout' => $today->copy()->addDays(6)->format('Y-m-d'),
                    'nights' => 2,
                    'room_count' => 1,
                    'guests' => 4,
                    'status' => 'confirmed',
                    'payment_status' => 'paid',
                    'notes' => 'Keluarga dengan dua anak, mohon ekstra handuk.',
                    'created_at' => $yearStart->copy()->addMonths(max(0, min($currentMonth - 1, 2))),
                ],
                [
                    'order_id' => 'CLARO-DEMO-105',
                    'room' => $pickRoom(2),
                    'name' => 'PT Maju Nusantara (Korporat)',
                    'email' => 'travel@majunusantara.example.com',
                    'phone' => '+62 411-8890-210',
                    'checkin' => $today->copy()->subDays(18)->format('Y-m-d'),
                    'checkout' => $today->copy()->subDays(15)->format('Y-m-d'),
                    'nights' => 3,
                    'room_count' => 3,
                    'guests' => 5,
                    'status' => 'confirmed',
                    'payment_status' => 'paid',
                    'notes' => 'Rombongan peserta seminar Ballroom Sandeq.',
                    'created_at' => $yearStart->copy()->addMonths(max(0, min($currentMonth - 1, 4))),
                ],
                [
                    'order_id' => 'CLARO-DEMO-106',
                    'room' => $pickRoom(4),
                    'name' => 'Hendra Wijaya',
                    'email' => 'hendra.wijaya@example.com',
                    'phone' => '+62 815-2211-9088',
                    'checkin' => $today->copy()->subDays(32)->format('Y-m-d'),
                    'checkout' => $today->copy()->subDays(30)->format('Y-m-d'),
                    'nights' => 2,
                    'room_count' => 1,
                    'guests' => 2,
                    'status' => 'confirmed',
                    'payment_status' => 'paid',
                    'notes' => 'VIP Penthouse weekend stay.',
                    'created_at' => $yearStart->copy()->addMonths(max(0, min($currentMonth - 1, 6))),
                ],
                [
                    'order_id' => 'CLARO-DEMO-107',
                    'room' => $pickRoom(0),
                    'name' => 'Nadia Putri',
                    'email' => 'nadia.putri@example.com',
                    'phone' => '+62 821-9833-4112',
                    'checkin' => $today->copy()->addDays(7)->format('Y-m-d'),
                    'checkout' => $today->copy()->addDays(9)->format('Y-m-d'),
                    'nights' => 2,
                    'room_count' => 1,
                    'guests' => 2,
                    'status' => 'cancelled',
                    'payment_status' => 'refunded',
                    'notes' => 'Perubahan jadwal perjalanan.',
                    'created_at' => $yearStart->copy()->addMonths(max(0, min($currentMonth - 1, 8))),
                ],
                [
                    'order_id' => 'CLARO-DEMO-108',
                    'room' => $pickRoom(1),
                    'name' => 'Rizky Ramadhan',
                    'email' => 'rizky.ramadhan@example.com',
                    'phone' => '+62 852-1190-6531',
                    'checkin' => $today->copy()->subDays(2)->format('Y-m-d'),
                    'checkout' => $today->copy()->format('Y-m-d'),
                    'nights' => 2,
                    'room_count' => 1,
                    'guests' => 2,
                    'status' => 'confirmed',
                    'payment_status' => 'paid',
                    'notes' => 'Check-out pagi pukul 09.00 WITA.',
                    'created_at' => Carbon::now()->subDays(5),
                ],
            ];

            foreach ($samples as $item) {
                if (Booking::where('midtrans_order_id', $item['order_id'])->exists()) {
                    continue;
                }

                $unitPrice = (int) ($item['room']?->price ?: 1200000);
                $totalPrice = $unitPrice * $item['room_count'] * $item['nights'];

                $booking = new Booking([
                    'room_id' => $item['room']->id,
                    'name' => $item['name'],
                    'email' => $item['email'],
                    'phone' => $item['phone'],
                    'checkin' => $item['checkin'],
                    'checkout' => $item['checkout'],
                    'guests' => $item['guests'],
                    'nights' => $item['nights'],
                    'room_count' => $item['room_count'],
                    'total_price' => $totalPrice,
                    'notes' => $item['notes'],
                    'status' => $item['status'],
                    'payment_status' => $item['payment_status'],
                    'midtrans_order_id' => $item['order_id'],
                    'promo_code' => null,
                    'discount_amount' => 0,
                ]);
                $booking->created_at = $item['created_at'];
                $booking->updated_at = $item['created_at'];
                $booking->save();
            }
        } catch (\Throwable $e) {
            // Gracefully skip if DB connection is read-only or unavailable
        }
    }
}

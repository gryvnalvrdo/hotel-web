<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin
        DB::table('admins')->insert([
            'username' => 'admin',
            'password' => Hash::make('adminHotel123'),
            'name' => 'Administrator',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Rooms
        $rooms = [
            ['id' => 1, 'name' => 'Deluxe Room', 'short_description' => 'Kenyamanan standar premium dengan pemandangan kota.', 'capacity' => 2, 'price' => 850000, 'total_rooms' => 30],
            ['id' => 2, 'name' => 'Junior Suite', 'short_description' => 'Ruangan luas dengan area bersantai terpisah.', 'capacity' => 2, 'price' => 1250000, 'total_rooms' => 15],
            ['id' => 3, 'name' => 'Executive Suite', 'short_description' => 'Kemewahan paripurna untuk pengalaman tak terlupakan.', 'capacity' => 2, 'price' => 1800000, 'total_rooms' => 10],
            ['id' => 4, 'name' => 'Family Room', 'short_description' => 'Kamar ideal untuk liburan keluarga yang menyenangkan.', 'capacity' => 4, 'price' => 2200000, 'total_rooms' => 5],
            ['id' => 5, 'name' => 'Presidential Suite', 'short_description' => 'Tingkat kemewahan tertinggi dengan layanan VIP eksklusif.', 'capacity' => 4, 'price' => 5000000, 'total_rooms' => 2],
        ];
        DB::table('rooms')->insert($rooms);

        // 3. Room Images
        $roomImages = [
            ['room_id' => 1, 'file_path' => 'images/rooms/deluxe1.jpg'],
            ['room_id' => 1, 'file_path' => 'images/rooms/deluxe2.jpg'],
            ['room_id' => 1, 'file_path' => 'images/rooms/deluxe3.jpg'],
            ['room_id' => 1, 'file_path' => 'images/rooms/deluxe4.jpg'],
            ['room_id' => 1, 'file_path' => 'images/rooms/deluxe5.jpeg'],

            ['room_id' => 3, 'file_path' => 'images/rooms/executive1.jpg'],
            ['room_id' => 3, 'file_path' => 'images/rooms/executive2.jpg'],

            ['room_id' => 2, 'file_path' => 'images/rooms/junior1.jpg'],
            ['room_id' => 2, 'file_path' => 'images/rooms/junior2.jpg'],
            ['room_id' => 2, 'file_path' => 'images/rooms/junior3.jpg'],
            ['room_id' => 2, 'file_path' => 'images/rooms/junior4.jpg'],

            ['room_id' => 4, 'file_path' => 'images/rooms/deluxe1.jpg'],
            ['room_id' => 5, 'file_path' => 'images/rooms/executive1.jpg'],
        ];
        DB::table('room_images')->insert($roomImages);

        // 4. Room Facilities
        $facilities = [];
        foreach (range(1, 5) as $roomId) {
            $facilities[] = ['room_id' => $roomId, 'category' => 'utama', 'facility_name' => 'Free Wi-Fi', 'icon' => 'bi bi-wifi'];
            $facilities[] = ['room_id' => $roomId, 'category' => 'utama', 'facility_name' => 'Smart TV 55"', 'icon' => 'bi bi-tv'];
            $facilities[] = ['room_id' => $roomId, 'category' => 'kamar', 'facility_name' => 'AC Central', 'icon' => 'bi bi-snow'];
            $facilities[] = ['room_id' => $roomId, 'category' => 'kamar', 'facility_name' => 'Mini Bar', 'icon' => 'bi bi-cup-straw'];
            $facilities[] = ['room_id' => $roomId, 'category' => 'bathroom', 'facility_name' => 'Water Heater', 'icon' => 'bi bi-droplet-half'];
        }
        DB::table('room_facilities')->insert($facilities);

        // 5. Home Slider
        DB::table('home_slider')->insert([
            ['image_path' => 'images/slider/slider1.jpg'],
            ['image_path' => 'images/slider/slider2.jpg'],
            ['image_path' => 'images/slider/slider3.jpg'],
        ]);

        // 6. Home Facilities
        $homeFacilities = [
            ['id' => 1, 'title' => 'Executive Lounge', 'description' => 'Lounge eksklusif untuk bersantai dan pertemuan bisnis.'],
            ['id' => 2, 'title' => 'Sunachi Suki', 'description' => 'Nikmati hidangan Suki otentik dengan bahan pilihan terbaik.'],
            ['id' => 3, 'title' => 'Carita Lounge', 'description' => 'Tempat yang nyaman untuk ngopi dan bercengkerama.'],
            ['id' => 4, 'title' => 'Koi Japanese Restaurant', 'description' => 'Cita rasa Jepang autentik yang menggugah selera.'],
            ['id' => 5, 'title' => 'Vegas Lounge', 'description' => 'Hiburan malam eksklusif dengan live music.'],
            ['id' => 6, 'title' => 'Legend Coffee', 'description' => 'Kedai kopi legendaris dengan aroma kopi pilihan.'],
            ['id' => 7, 'title' => 'Sky Lounge', 'description' => 'Menikmati pemandangan kota dari ketinggian.'],
            ['id' => 8, 'title' => 'Milena Spa', 'description' => 'Relaksasi tubuh dan pikiran dengan terapis profesional.'],
            ['id' => 9, 'title' => 'Karaoke', 'description' => 'Fasilitas hiburan bernyanyi untuk keluarga dan teman.'],
            ['id' => 10, 'title' => 'Liquid Club', 'description' => 'Club malam berkelas untuk melepas penat.'],
        ];
        DB::table('home_facilities')->insert($homeFacilities);

        // 7. Home Facility Images
        $facilityImages = [
            // Executive Lounge
            ['facility_id' => 1, 'image_path' => 'images/facilities/executivelounge1.jpg'],
            ['facility_id' => 1, 'image_path' => 'images/facilities/executivelounge2.jpg'],
            ['facility_id' => 1, 'image_path' => 'images/facilities/executivelounge3.jpg'],
            // Sunachi
            ['facility_id' => 2, 'image_path' => 'images/facilities/sunachi1.jpg'],
            ['facility_id' => 2, 'image_path' => 'images/facilities/sunachi2.jpg'],
            ['facility_id' => 2, 'image_path' => 'images/facilities/sunachi3.jpg'],
            ['facility_id' => 2, 'image_path' => 'images/facilities/sunachi4.jpg'],
            ['facility_id' => 2, 'image_path' => 'images/facilities/sunachi5.jpg'],
            ['facility_id' => 2, 'image_path' => 'images/facilities/sunachi6.jpg'],
            ['facility_id' => 2, 'image_path' => 'images/facilities/sunachi7.jpg'],
            ['facility_id' => 2, 'image_path' => 'images/facilities/sunachi8.jpg'],
            ['facility_id' => 2, 'image_path' => 'images/facilities/sunachi9.jpg'],
            ['facility_id' => 2, 'image_path' => 'images/facilities/sunachi10.jpg'],
            ['facility_id' => 2, 'image_path' => 'images/facilities/sunachi11.jpg'],
            ['facility_id' => 2, 'image_path' => 'images/facilities/sunachi12.jpg'],
            // Carita
            ['facility_id' => 3, 'image_path' => 'images/facilities/caritalounge.jpg'],
            // Koi
            ['facility_id' => 4, 'image_path' => 'images/facilities/koi1.jpg'],
            ['facility_id' => 4, 'image_path' => 'images/facilities/koi2.jpg'],
            ['facility_id' => 4, 'image_path' => 'images/facilities/koi3.jpg'],
            // Vegas
            ['facility_id' => 5, 'image_path' => 'images/facilities/vegaslounge1.jpg'],
            ['facility_id' => 5, 'image_path' => 'images/facilities/vegaslounge2.jpg'],
            ['facility_id' => 5, 'image_path' => 'images/facilities/vegaslounge3.jpg'],
            // Legend
            ['facility_id' => 6, 'image_path' => 'images/facilities/legendcoffee.jpg'],
            // Sky
            ['facility_id' => 7, 'image_path' => 'images/facilities/sky1.jpg'],
            ['facility_id' => 7, 'image_path' => 'images/facilities/sky2.jpg'],
            ['facility_id' => 7, 'image_path' => 'images/facilities/sky3.jpg'],
            // Milena
            ['facility_id' => 8, 'image_path' => 'images/facilities/milena1.jpg'],
            ['facility_id' => 8, 'image_path' => 'images/facilities/milena2.jpg'],
            ['facility_id' => 8, 'image_path' => 'images/facilities/milena3.jpg'],
            // Karaoke
            ['facility_id' => 9, 'image_path' => 'images/facilities/karaoke1.jpg'],
            ['facility_id' => 9, 'image_path' => 'images/facilities/karaoke2.jpg'],
            ['facility_id' => 9, 'image_path' => 'images/facilities/karaoke3.jpg'],
            ['facility_id' => 9, 'image_path' => 'images/facilities/karaoke4.jpg'],
            ['facility_id' => 9, 'image_path' => 'images/facilities/karaoke5.jpg'],
            // Liquid
            ['facility_id' => 10, 'image_path' => 'images/facilities/liquid1.jpg'],
            ['facility_id' => 10, 'image_path' => 'images/facilities/liquid2.jpg'],
            ['facility_id' => 10, 'image_path' => 'images/facilities/liquid3.jpg'],
            ['facility_id' => 10, 'image_path' => 'images/facilities/liquid4.jpg'],
        ];
        DB::table('home_facility_images')->insert($facilityImages);

        // 8. Footer Branding
        DB::table('footer_branding')->insert([
            'hotel_name' => 'Web Hotel',
            'tagline' => 'Kemewahan Berkelas & Pelayanan Eksklusif di Jantung Kota.',
        ]);

        // 9. Footer Bottom
        DB::table('footer_bottom')->insert([
            'text' => 'Copyright &copy; ' . date('Y') . ' Web Hotel. All Rights Reserved.',
        ]);

        // 10. Footer Socials
        DB::table('footer_social')->insert([
            ['platform' => 'Instagram', 'url' => 'https://instagram.com', 'icon_class' => 'bi bi-instagram', 'display_order' => 1],
            ['platform' => 'Facebook', 'url' => 'https://facebook.com', 'icon_class' => 'bi bi-facebook', 'display_order' => 2],
            ['platform' => 'Twitter', 'url' => 'https://twitter.com', 'icon_class' => 'bi bi-twitter', 'display_order' => 3],
        ]);

        // 11. Footer Partners
        DB::table('footer_partners')->insert([
            ['name' => 'Agoda', 'url' => 'https://agoda.com', 'logo_path' => 'images/partners/agoda.png', 'display_order' => 1],
            ['name' => 'Traveloka', 'url' => 'https://traveloka.com', 'logo_path' => 'images/partners/traveloka.png', 'display_order' => 2],
            ['name' => 'Booking.com', 'url' => 'https://booking.com', 'logo_path' => 'images/partners/booking.png', 'display_order' => 3],
        ]);

        // 12. Footer Contacts
        DB::table('footer_contact')->insert([
            ['type' => 'address', 'value' => 'Jl. Boulevard Sudirman No. 123, Kota Fiktif, Indonesia', 'icon_class' => 'bi bi-geo-alt-fill', 'display_order' => 1],
            ['type' => 'phone', 'value' => '+62 812 3456 7890', 'icon_class' => 'bi bi-telephone-fill', 'display_order' => 2],
            ['type' => 'email', 'value' => 'hello@webhotel.local', 'icon_class' => 'bi bi-envelope-fill', 'display_order' => 3],
        ]);
        
        // 13. Promos
        DB::table('promos')->insert([
            ['code' => 'WELCOME20', 'discount_type' => 'percent', 'discount_amount' => 20, 'is_active' => 1, 'valid_until' => '2026-12-31'],
            ['code' => 'FLAT100K', 'discount_type' => 'fixed', 'discount_amount' => 100000, 'is_active' => 1, 'valid_until' => '2026-12-31'],
        ]);
    }
}

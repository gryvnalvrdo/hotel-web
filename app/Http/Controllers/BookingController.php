<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Booking;
use App\Models\FooterBranding;
use App\Models\FooterSocial;
use App\Models\FooterPartner;
use App\Models\FooterContact;
use App\Models\FooterBottom;
use App\Mail\BookingConfirmedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $checkin  = $request->get('checkin');
        $checkout = $request->get('checkout');
        $allRooms = Room::with('images')->get()->map(function ($room) use ($checkin, $checkout) {
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
            }

            $img = $room->images->first()?->file_path;
            if (empty($img)) {
                $room->image = asset('images/slider/slider1.jpg');
            } else {
                if (str_starts_with($img, 'http')) {
                    $room->image = $img;
                } else {
                    $img = preg_replace('/^public\//', '', $img);
                    $img = ltrim($img, '/');
                    if (!str_starts_with($img, 'images/') && !str_starts_with($img, 'storage/')) {
                        // Jika DB hanya menyimpan 'junior1.jpg' atau 'rooms/junior1.jpg'
                        $img = 'images/rooms/' . basename($img);
                    }
                    $room->image = asset($img);
                }
            }
            $room->available = true; // default
            if ($checkin && $checkout) {
                $room->available = $this->isRoomAvailable($room->id, $checkin, $checkout);
            }

            return $room;
        });
        $roomsJs = $allRooms->mapWithKeys(fn($r) => [
            (string) $r->id => [
                'name'      => $r->name,
                'capacity'  => (int) $r->capacity,
                'price'     => (int) $r->price,
                'available' => $r->available,
            ]
        ]);
        $selectedRoom = null;
        if ($request->has('room_id')) {
            $selectedRoom = Room::with('images')->find($request->room_id);
        }

        $footerData = $this->getFooterData();

        return view('booking.index', array_merge(
            compact('allRooms', 'roomsJs', 'selectedRoom', 'checkin', 'checkout'),
            $footerData
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_id'        => 'required|exists:rooms,id',
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|max:255',
            'phone'          => 'required|string|max:30',
            'checkin'        => 'required|date|after_or_equal:today',
            'checkout'       => 'required|date|after:checkin',
            'guests'         => 'required|integer|min:1|max:10',
            'nights'         => 'required|integer|min:1',
            'total_price'    => 'required|numeric|min:0',
            'room_count'     => 'required|integer|min:1',
            'special_request'=> 'nullable|string|max:1000',
            'promo_code'     => 'nullable|string|max:50',
            'payment_method' => 'nullable|string|in:midtrans,pay_at_hotel',
            'room_details'   => 'nullable|string',
        ]);
        if (!empty($validated['room_details'])) {
            try {
                $details = json_decode($validated['room_details'], true);
                if (is_array($details) && count($details) > 1) {
                    $summaryParts = [];
                    foreach ($details as $d) {
                        $summaryParts[] = ($d['qty'] ?? 1) . 'x ' . ($d['name'] ?? 'Kamar');
                    }
                    $multiRoomStr = "[Multi-Room: " . implode(", ", $summaryParts) . "]";
                    $validated['special_request'] = $multiRoomStr . (!empty($validated['special_request']) ? " | " . $validated['special_request'] : "");
                }
            } catch (\Throwable $e) {}
        }
        $nights = max(1, Carbon::parse($validated['checkin'])->diffInDays(Carbon::parse($validated['checkout'])));
        $validated['nights'] = $nights;

        $details = [];
        if (!empty($validated['room_details'])) {
            $decoded = json_decode($validated['room_details'], true);
            if (is_array($decoded) && count($decoded) > 0) {
                $details = $decoded;
            }
        }

        $orderId = 'Hotel-' . strtoupper(Str::random(8)) . '-' . time();
        $paymentMethod = $validated['payment_method'] ?? 'midtrans';

        $bookingResult = DB::transaction(function () use (&$validated, $details, $nights, $orderId, $paymentMethod) {
            $totalCap = 0;
            $backendTotal = 0;

            if (count($details) > 0) {
                foreach ($details as $item) {
                    $rId = (int) ($item['id'] ?? 0);
                    $rQty = max(1, (int) ($item['qty'] ?? 1));
                    $rObj = Room::where('id', $rId)->lockForUpdate()->first();
                    if (!$rObj || $rObj->getAvailableStock($validated['checkin'], $validated['checkout']) < $rQty) {
                        $rName = $rObj ? $rObj->name : 'Kamar';
                        $avail = $rObj ? $rObj->getAvailableStock($validated['checkin'], $validated['checkout']) : 0;
                        return [
                            'error_key' => 'room_id',
                            'error_msg' => "Maaf, tipe kamar $rName hanya tersisa $avail unit untuk tanggal yang dipilih ({$validated['checkin']} s/d {$validated['checkout']}).",
                        ];
                    }
                    $totalCap += ((int) ($rObj->capacity ?: 2)) * $rQty;
                    $backendTotal += ((int) $rObj->price) * $rQty * $nights;
                }
            } else {
                $rQty = max(1, (int) $validated['room_count']);
                $rObj = Room::where('id', $validated['room_id'])->lockForUpdate()->first();
                if (!$rObj || $rObj->getAvailableStock($validated['checkin'], $validated['checkout']) < $rQty) {
                    $avail = $rObj ? $rObj->getAvailableStock($validated['checkin'], $validated['checkout']) : 0;
                    return [
                        'error_key' => 'room_id',
                        'error_msg' => "Maaf, tipe kamar tersebut hanya tersisa $avail unit untuk tanggal yang dipilih.",
                    ];
                }
                $totalCap = ((int) ($rObj->capacity ?: 2)) * $rQty;
                $backendTotal = ((int) $rObj->price) * $rQty * $nights;
            }

            if ($totalCap < (int) $validated['guests']) {
                return [
                    'error_key' => 'guests',
                    'error_msg' => "Maaf, kapasitas total kamar terpilih ($totalCap tamu) tidak mencukupi untuk " . $validated['guests'] . " tamu. Silakan tambahkan jumlah kamar atau pilih tipe kamar yang lebih besar.",
                ];
            }

            $discount = 0;
            if (!empty($validated['promo_code'])) {
                $promo = \App\Models\Promo::where('code', $validated['promo_code'])->first();
                if ($promo && $promo->isValid()) {
                    $discount = $promo->discount_type === 'percent'
                        ? ($backendTotal * ($promo->discount_amount / 100))
                        : $promo->discount_amount;
                } else {
                    $validated['promo_code'] = null;
                }
            }

            $finalTotal = max(0, $backendTotal - $discount);
            $validated['total_price'] = $finalTotal;
            $validated['discount_amount'] = $discount;

            $booking = Booking::create(array_merge($validated, [
                'status'            => $paymentMethod === 'pay_at_hotel' ? 'confirmed' : 'pending',
                'payment_status'    => $paymentMethod === 'pay_at_hotel' ? 'pay_at_hotel' : 'unpaid',
                'midtrans_order_id' => $orderId,
            ]));

            return ['booking' => $booking];
        });

        if (isset($bookingResult['error_key'])) {
            return back()->withErrors([
                $bookingResult['error_key'] => $bookingResult['error_msg'],
            ])->withInput();
        }

        $booking = $bookingResult['booking'];

        if ($paymentMethod === 'pay_at_hotel') {
            $this->sendConfirmationEmail($booking);
            return redirect()->route('booking.success', ['id' => $booking->id]);
        }

        $snapToken = $this->createMidtransToken($booking);
        $booking->update(['midtrans_token' => $snapToken]);

        return redirect()->route('booking.payment', ['id' => $booking->id]);
    }

    public function payment(Request $request)
    {
        $booking = Booking::with('room')->findOrFail($request->id);
        if ($booking->payment_status === 'paid') {
            return redirect()->route('booking.success', ['id' => $booking->id]);
        }

        $footerData = $this->getFooterData();
        return view('booking.payment', array_merge(compact('booking'), $footerData));
    }

    public function success(Request $request)
    {
        $booking = Booking::with('room')->findOrFail($request->id);
        $footerData = $this->getFooterData();
        return view('booking.success', array_merge(compact('booking'), $footerData));
    }

    public function invoice(Request $request)
    {
        $booking = Booking::with('room')->findOrFail($request->id);
        return view('booking.invoice', compact('booking'));
    }

    
    public function simulatePay(Request $request, int $id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update([
            'payment_status' => 'paid',
            'status'         => 'confirmed',
        ]);
        $this->sendConfirmationEmail($booking);
        return redirect()->route('booking.success', ['id' => $id]);
    }

    
    public function midtransCallback(Request $request)
    {
        $serverKey = (string) config('services.midtrans.server_key');
        if ($serverKey === '') {
            return response()->json(['message' => 'Payment gateway not configured'], 503);
        }

        $orderId      = (string) $request->order_id;
        $statusCode   = (string) $request->status_code;
        $grossAmount  = (string) $request->gross_amount;
        $signature    = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);
        if (!hash_equals($signature, (string) $request->signature_key)) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $booking = Booking::where('midtrans_order_id', $orderId)->first();
        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        $transactionStatus = $request->transaction_status;
        $fraudStatus       = $request->fraud_status;
        $wasAlreadyPaid    = $booking->payment_status === 'paid';

        if ($transactionStatus === 'capture') {
            if ($fraudStatus === 'accept' && !$wasAlreadyPaid) {
                $booking->update(['payment_status' => 'paid', 'status' => 'confirmed']);
                $this->sendConfirmationEmail($booking);
            }
        } elseif (in_array($transactionStatus, ['settlement', 'success'])) {
            if (!$wasAlreadyPaid) {
                $booking->update(['payment_status' => 'paid', 'status' => 'confirmed']);
                $this->sendConfirmationEmail($booking);
            }
        } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            if (!$wasAlreadyPaid) {
                $booking->update(['payment_status' => 'unpaid', 'status' => 'cancelled']);
            }
        }

        return response()->json(['message' => 'OK']);
    }

    
    private function sendConfirmationEmail(Booking $booking): void
    {
        try {
            Mail::to($booking->email)->send(new BookingConfirmedMail($booking));
        } catch (\Exception $e) {
            Log::error('Gagal mengirim email konfirmasi reservasi: ' . $e->getMessage());
        }
    }

    
    private function isRoomAvailable(int $roomId, string $checkin, string $checkout, int $requestedQty = 1): bool
    {
        $room = \App\Models\Room::find($roomId);
        if (!$room) return false;
        return $room->getAvailableStock($checkin, $checkout) >= $requestedQty;
    }

    
    public function checkAvailability(Request $request)
    {
        $checkin = $request->get('checkin', now()->format('Y-m-d'));
        $checkout = $request->get('checkout', now()->addDay()->format('Y-m-d'));
        
        $rooms = \App\Models\Room::all();
        $result = [];
        foreach ($rooms as $r) {
            $total = (int)($r->total_rooms ?: 20);
            $booked = $r->getActiveBookingsCount($checkin, $checkout);
            $avail = max(0, $total - $booked);
            $result[$r->id] = [
                'total' => $total,
                'booked' => $booked,
                'available' => $avail,
                'is_full' => $avail <= 0,
            ];
        }
        return response()->json($result);
    }

    
    private function createMidtransToken(Booking $booking): ?string
    {
        if (!config('services.midtrans.server_key')) {
            return null; // Skip jika belum dikonfigurasi
        }

        try {
            \Midtrans\Config::$serverKey    = config('services.midtrans.server_key');
            \Midtrans\Config::$isProduction = config('services.midtrans.is_production', false);
            \Midtrans\Config::$isSanitized  = true;
            \Midtrans\Config::$is3ds        = true;

            $params = [
                'transaction_details' => [
                    'order_id'     => $booking->midtrans_order_id,
                    'gross_amount' => (int) $booking->total_price,
                ],
                'customer_details' => [
                    'first_name' => $booking->name,
                    'email'      => $booking->email,
                    'phone'      => $booking->phone,
                ],
                'item_details' => [
                    [
                        'id'       => 'ROOM-' . $booking->room_id,
                        'price'    => (int) $booking->room?->price,
                        'quantity' => (int) $booking->nights * (int) $booking->room_count,
                        'name'     => $booking->room?->name ?? 'Hotel Room',
                    ]
                ],
            ];

            return \Midtrans\Snap::getSnapToken($params);
        } catch (\Exception $e) {
            \Log::error('Midtrans error: ' . $e->getMessage());
            return null;
        }
    }

    
    private function getFooterData(): array
    {
        return Cache::remember('hotel_footer_data', 300, function () {
            return [
                'branding'     => FooterBranding::first(),
                'socials'      => FooterSocial::orderBy('display_order')->get(),
                'partners'     => FooterPartner::orderBy('display_order')->get(),
                'contacts'     => FooterContact::orderBy('display_order')->get(),
                'footerBottom' => FooterBottom::first(),
            ];
        });
    }
}

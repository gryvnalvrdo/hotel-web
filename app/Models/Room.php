<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'name', 'short_description', 'full_description', 'price', 'capacity', 'total_rooms', 'floor_location'
    ];

    public function getActiveBookingsCount(?string $checkin = null, ?string $checkout = null): int
    {
        if (!$checkin || !$checkout) {
            $checkin = now()->format('Y-m-d');
            $checkout = now()->addDay()->format('Y-m-d');
        }

        $startDate = \Carbon\Carbon::parse($checkin);
        $endDate = \Carbon\Carbon::parse($checkout);
        if ($endDate->lessThanOrEqualTo($startDate)) {
            $endDate = (clone $startDate)->addDay();
        }

        $bookings = Booking::whereNotIn('status', ['cancelled', 'checked_out'])
            ->whereNotIn('payment_status', ['cancelled', 'refunded'])
            ->where(function ($query) {
                $query->where('payment_status', '!=', 'unpaid')
                      ->orWhere('created_at', '>=', now()->subHours(24));
            })
            ->where('checkin', '<', $endDate->format('Y-m-d'))
            ->where('checkout', '>', $startDate->format('Y-m-d'))
            ->get();

        $maxBooked = 0;
        $curr = clone $startDate;
        while ($curr->lessThan($endDate)) {
            $dayStr = $curr->format('Y-m-d');
            $dayBooked = 0;
            foreach ($bookings as $b) {
                if ($b->checkin <= $dayStr && $b->checkout > $dayStr) {
                    $qty = 0;
                    if (!empty($b->room_details)) {
                        $details = json_decode($b->room_details, true);
                        if (is_array($details)) {
                            foreach ($details as $d) {
                                if (($d['id'] ?? null) == $this->id) {
                                    $qty += (int)($d['qty'] ?? 1);
                                }
                            }
                        }
                    }
                    if ($qty === 0 && $b->room_id == $this->id) {
                        $qty = (int)($b->room_count ?: 1);
                    }
                    $dayBooked += $qty;
                }
            }
            if ($dayBooked > $maxBooked) {
                $maxBooked = $dayBooked;
            }
            $curr->addDay();
        }

        return $maxBooked;
    }

    public function getAvailableStock(?string $checkin = null, ?string $checkout = null): int
    {
        $total = (int) ($this->total_rooms ?: 20);
        $booked = $this->getActiveBookingsCount($checkin, $checkout);
        return max(0, $total - $booked);
    }

    public function getActiveBookingsList(?string $checkin = null, ?string $checkout = null)
    {
        if (!$checkin || !$checkout) {
            $checkin = now()->format('Y-m-d');
            $checkout = now()->addDay()->format('Y-m-d');
        }

        $bookings = Booking::whereNotIn('status', ['cancelled', 'checked_out'])
            ->whereNotIn('payment_status', ['cancelled', 'refunded'])
            ->where(function ($query) {
                $query->where('payment_status', '!=', 'unpaid')
                      ->orWhere('created_at', '>=', now()->subHours(24));
            })
            ->where('checkin', '<', $checkout)
            ->where('checkout', '>', $checkin)
            ->orderBy('checkin', 'asc')
            ->get();

        return $bookings->filter(function ($b) {
            if (!empty($b->room_details)) {
                $details = json_decode($b->room_details, true);
                if (is_array($details)) {
                    foreach ($details as $d) {
                        if (($d['id'] ?? null) == $this->id) return true;
                    }
                }
            }
            return $b->room_id == $this->id;
        });
    }

    public function images()
    {
        return $this->hasMany(RoomImage::class);
    }

    public function facilities()
    {
        return $this->hasMany(RoomFacility::class);
    }

    public function facilitiesByCategory(string $category)
    {
        return $this->facilities()->where('category', $category)->get();
    }

    public function thumbnail()
    {
        return $this->images()->first();
    }
}

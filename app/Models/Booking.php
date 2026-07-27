<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'room_id', 'name', 'email', 'phone',
        'checkin', 'checkout', 'guests', 'nights',
        'room_count', 'total_price', 'notes',
        'status', 'payment_status',
        'midtrans_order_id', 'midtrans_token', 'room_details',
        'special_request',
        'promo_code',
        'discount_amount'
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomFacility extends Model
{
    protected $table = 'room_facilities';
    protected $fillable = ['room_id', 'category', 'facility_name', 'icon'];

    public function getNameAttribute()
    {
        return $this->facility_name;
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}

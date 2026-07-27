<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConferenceRoom extends Model
{
    protected $table = 'conference_rooms';
    protected $fillable = ['name', 'description', 'width', 'length', 'capacity'];

    public function images()
    {
        return $this->hasMany(ConferenceRoomImage::class, 'room_id')->orderBy('display_order');
    }
}

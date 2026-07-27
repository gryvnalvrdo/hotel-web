<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConferenceRoomImage extends Model
{
    protected $table = 'conference_room_images';
    protected $fillable = ['room_id', 'image_path', 'display_order'];

    public function room()
    {
        return $this->belongsTo(ConferenceRoom::class, 'room_id');
    }
}

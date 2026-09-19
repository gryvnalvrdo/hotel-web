<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConferenceRoom extends Model
{
    protected $table = 'conference_rooms';
    public $timestamps = false;
    protected $fillable = ['name', 'description', 'width', 'length', 'capacity', 'created_at'];

    public function images()
    {
        return $this->hasMany(ConferenceRoomImage::class, 'room_id')->orderBy('display_order');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeFacilityImage extends Model
{
    protected $table = 'home_facility_images';
    protected $fillable = ['facility_id', 'image_path'];

    public function facility()
    {
        return $this->belongsTo(HomeFacility::class, 'facility_id');
    }
}

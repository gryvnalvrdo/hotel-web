<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeFacility extends Model
{
    protected $table = 'home_facilities';
    protected $fillable = ['title', 'description'];

    public function images()
    {
        return $this->hasMany(HomeFacilityImage::class, 'facility_id');
    }

    public function thumbnail()
    {
        return $this->images()->first();
    }
}

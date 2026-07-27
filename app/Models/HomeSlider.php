<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeSlider extends Model
{
    protected $table = 'home_slider';
    protected $fillable = ['image_path', 'display_order'];
}

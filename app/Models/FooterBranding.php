<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FooterBranding extends Model
{
    protected $table = 'footer_branding';
    protected $fillable = ['hotel_name', 'tagline'];
}

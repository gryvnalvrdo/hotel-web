<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FooterSocial extends Model
{
    protected $table = 'footer_social';
    protected $fillable = ['icon_class', 'url', 'display_order'];
}

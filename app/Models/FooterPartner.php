<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FooterPartner extends Model
{
    protected $table = 'footer_partners';
    protected $fillable = ['name', 'logo_path', 'url', 'display_order'];
}

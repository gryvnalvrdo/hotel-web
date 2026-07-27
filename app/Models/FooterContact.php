<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FooterContact extends Model
{
    protected $table = 'footer_contact';
    protected $fillable = ['icon_class', 'value', 'display_order'];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'discount_type',
        'discount_amount',
        'is_active',
        'valid_until',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'valid_until' => 'date',
    ];

    public function isValid()
    {
        if (!$this->is_active) return false;
        if ($this->valid_until && $this->valid_until->isPast()) return false;
        return true;
    }
}

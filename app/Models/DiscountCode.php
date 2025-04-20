<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiscountCode extends Model
{
    protected $fillable = [
        'code',
        'discount_amount',
        'discount_type',
        'valid_from',
        'valid_until',
        'is_active'
    ];

    protected $casts = [
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
        'is_active' => 'boolean'
    ];

    public function isValid()
    {
        $now = now();
        return $this->is_active
            && $now->gte($this->valid_from)
            && ($this->valid_until === null || $now->lte($this->valid_until));
    }
}
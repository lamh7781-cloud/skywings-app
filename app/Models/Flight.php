<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    public const CABIN_CLASSES = [
        'economy',
        'premium_economy',
        'business',
        'first',
    ];

    protected $fillable = [
        'flight_number',
        'origin',
        'destination',
        'departure_time',
        'price',
        'cabin_class',
        'seats_available',
    ];

    protected function casts(): array
    {
        return [
            'departure_time' => 'datetime',
            'price' => 'decimal:2',
            'seats_available' => 'integer',
        ];
    }
}

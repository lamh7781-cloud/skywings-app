<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    protected $fillable = [
        'flight_name',
        'departure',
        'destination',
        'flight_date',
        'price',
    ];

    protected function casts(): array
    {
        return [
            'flight_date' => 'date',
            'price' => 'decimal:2',
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FlightResource extends JsonResource
{
    /** @return array<string, mixed> */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'flight_number' => $this->flight_number,
            'origin' => $this->origin,
            'destination' => $this->destination,
            'departure_time' => $this->departure_time->toIso8601String(),
            'price' => (float) $this->price,
            'cabin_class' => $this->cabin_class,
            'seats_available' => $this->seats_available,
        ];
    }
}

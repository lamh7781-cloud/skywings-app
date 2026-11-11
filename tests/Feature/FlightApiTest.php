<?php

namespace Tests\Feature;

use App\Models\Flight;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FlightApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_search_returns_matching_outbound_and_return_flights_for_the_selected_cabin_and_passengers(): void
    {
        $outbound = Flight::create([
            'flight_number' => 'VN101',
            'origin' => 'Hà Nội (HAN)',
            'destination' => 'Đà Nẵng (DAD)',
            'departure_time' => '2026-12-01 07:30:00',
            'price' => 1250000,
            'cabin_class' => 'economy',
            'seats_available' => 4,
        ]);
        Flight::create([
            'flight_number' => 'VN102',
            'origin' => 'Hà Nội (HAN)',
            'destination' => 'Đà Nẵng (DAD)',
            'departure_time' => '2026-12-01 08:30:00',
            'price' => 2250000,
            'cabin_class' => 'business',
            'seats_available' => 8,
        ]);
        Flight::create([
            'flight_number' => 'VN103',
            'origin' => 'Hà Nội (HAN)',
            'destination' => 'Đà Nẵng (DAD)',
            'departure_time' => '2026-12-01 09:30:00',
            'price' => 900000,
            'cabin_class' => 'economy',
            'seats_available' => 1,
        ]);
        $return = Flight::create([
            'flight_number' => 'VN104',
            'origin' => 'Đà Nẵng (DAD)',
            'destination' => 'Hà Nội (HAN)',
            'departure_time' => '2026-12-08 18:00:00',
            'price' => 1350000,
            'cabin_class' => 'economy',
            'seats_available' => 3,
        ]);

        $this->getJson('/api/flights/search?'.http_build_query([
            'origin' => 'Hà Nội',
            'destination' => 'Đà Nẵng',
            'departure_date' => '2026-12-01',
            'return_date' => '2026-12-08',
            'passengers' => 2,
            'cabin_class' => 'economy',
        ]))
            ->assertOk()
            ->assertJsonPath('data.outbound.0.id', $outbound->id)
            ->assertJsonPath('data.outbound.0.flight_number', 'VN101')
            ->assertJsonPath('data.return.0.id', $return->id)
            ->assertJsonPath('data.return.0.flight_number', 'VN104')
            ->assertJsonPath('meta.passengers', 2);
    }

    public function test_search_rejects_a_return_date_before_the_departure_date(): void
    {
        $this->getJson('/api/flights/search?'.http_build_query([
            'origin' => 'Hà Nội',
            'destination' => 'Đà Nẵng',
            'departure_date' => '2026-12-10',
            'return_date' => '2026-12-09',
            'passengers' => 1,
            'cabin_class' => 'economy',
        ]))
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['return_date']);
    }

    public function test_flight_details_are_available_as_json(): void
    {
        $flight = Flight::create([
            'flight_number' => 'VN201',
            'origin' => 'Hà Nội',
            'destination' => 'Đà Nẵng',
            'departure_time' => '2026-12-01 07:30:00',
            'price' => 1250000,
        ]);

        $this->getJson('/api/flights/'.$flight->id)
            ->assertOk()
            ->assertJsonPath('data.flight_number', 'VN201')
            ->assertJsonPath('data.cabin_class', 'economy');
    }
}

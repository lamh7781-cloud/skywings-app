<?php

namespace Tests\Feature;

use App\Models\Flight;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FlightCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_flight_pages_render_and_show_the_flights_asset_url(): void
    {
        $flight = Flight::create([
            'flight_name' => 'VN123',
            'departure' => 'Hà Nội',
            'destination' => 'Đà Nẵng',
            'flight_date' => '2026-12-01',
            'price' => 1250000,
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee(route('flights.index'), false);

        $this->get(route('flights.index'))
            ->assertOk()
            ->assertSee('css/style.css')
            ->assertSee('VN123')
            ->assertSee(route('bookings.show', $flight), false);

        $this->get(route('flights.show', $flight))
            ->assertOk()
            ->assertSee('Hà Nội')
            ->assertSee('Đà Nẵng');

        $this->get(route('bookings.show', $flight))
            ->assertOk()
            ->assertSee('Thông tin hành khách')
            ->assertSee('thông tin không được lưu');
    }

    public function test_flight_crud_routes_create_update_and_delete_a_flight(): void
    {
        $data = [
            'flight_name' => 'VN123',
            'departure' => 'Hà Nội',
            'destination' => 'Đà Nẵng',
            'flight_date' => '2026-12-01',
            'price' => '1250000.00',
        ];

        $this->post(route('flights.store'), $data)
            ->assertRedirect();
        $this->assertDatabaseHas('flights', ['flight_name' => 'VN123']);

        $flight = Flight::firstOrFail();
        $this->put(route('flights.update', $flight), [...$data, 'destination' => 'Huế'])
            ->assertRedirect(route('flights.show', $flight));
        $this->assertDatabaseHas('flights', ['id' => $flight->id, 'destination' => 'Huế']);

        $this->delete(route('flights.destroy', $flight))
            ->assertRedirect(route('flights.index'));
        $this->assertDatabaseMissing('flights', ['id' => $flight->id]);
    }

    public function test_flight_write_routes_validate_required_fields(): void
    {
        $this->from(route('flights.create'))
            ->post(route('flights.store'), [])
            ->assertRedirect(route('flights.create'))
            ->assertSessionHasErrors(['flight_name', 'departure', 'destination', 'flight_date', 'price']);
    }
}

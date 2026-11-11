<?php

namespace Tests\Feature;

use App\Models\Flight;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FlightCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_flight_pages_render_and_show_the_flights_asset_url(): void
    {
        $this->actingAs($this->createAdminUser());

        $flight = Flight::create([
            'flight_number' => 'VN123',
            'origin' => 'Hà Nội',
            'destination' => 'Đà Nẵng',
            'departure_time' => '2026-12-01 07:30:00',
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
        $this->actingAs($this->createAdminUser());

        $data = [
            'flight_number' => 'VN123',
            'origin' => 'Hà Nội',
            'destination' => 'Đà Nẵng',
            'departure_time' => '2026-12-01T07:30',
            'price' => '1250000.00',
            'cabin_class' => 'economy',
            'seats_available' => 80,
        ];

        $this->post(route('flights.store'), $data)
            ->assertRedirect();
        $this->assertDatabaseHas('flights', ['flight_number' => 'VN123']);

        $flight = Flight::firstOrFail();
        $this->put(route('flights.update', $flight), [...$data, 'destination' => 'Huế'])
            ->assertRedirect(route('flights.show', $flight));
        $this->assertDatabaseHas('flights', ['id' => $flight->id, 'destination' => 'Huế']);

        $this->delete(route('flights.destroy', $flight))
            ->assertRedirect(route('flights.index'));
        $this->assertDatabaseMissing('flights', ['id' => $flight->id]);
    }

    public function test_flight_list_filters_by_origin_destination_and_departure_date(): void
    {
        $this->actingAs($this->createAdminUser());

        Flight::create([
            'flight_number' => 'VN123',
            'origin' => 'Hà Nội',
            'destination' => 'Đà Nẵng',
            'departure_time' => '2026-12-01 07:30:00',
            'price' => 1250000,
        ]);
        Flight::create([
            'flight_number' => 'QH456',
            'origin' => 'Hà Nội',
            'destination' => 'Huế',
            'departure_time' => '2026-12-01 09:00:00',
            'price' => 1050000,
        ]);

        $this->get(route('flights.index', [
            'origin' => 'Hà Nội',
            'destination' => 'Đà Nẵng',
            'departure_date' => '2026-12-01',
        ]))
            ->assertOk()
            ->assertSee('VN123')
            ->assertDontSee('QH456');
    }

    public function test_flight_number_must_be_unique(): void
    {
        $this->actingAs($this->createAdminUser());

        Flight::create([
            'flight_number' => 'VN123',
            'origin' => 'Hà Nội',
            'destination' => 'Đà Nẵng',
            'departure_time' => '2026-12-01 07:30:00',
            'price' => 1250000,
        ]);

        $this->from(route('flights.create'))
            ->post(route('flights.store'), [
                'flight_number' => 'VN123',
                'origin' => 'Hà Nội',
                'destination' => 'Huế',
                'departure_time' => '2026-12-02T09:00',
                'price' => '1050000.00',
                'cabin_class' => 'economy',
                'seats_available' => 100,
            ])
            ->assertRedirect(route('flights.create'))
            ->assertSessionHasErrors(['flight_number']);

        $this->assertDatabaseCount('flights', 1);
    }

    public function test_flight_write_routes_validate_required_fields(): void
    {
        $this->actingAs($this->createAdminUser());

        $this->from(route('flights.create'))
            ->post(route('flights.store'), [])
            ->assertRedirect(route('flights.create'))
            ->assertSessionHasErrors([
                'flight_number',
                'origin',
                'destination',
                'departure_time',
                'price',
                'cabin_class',
                'seats_available',
            ]);
    }

    private function createAdminUser(): User
    {
        $admin = User::factory()->create();
        $admin->forceFill(['is_admin' => true])->save();

        return $admin;
    }
}

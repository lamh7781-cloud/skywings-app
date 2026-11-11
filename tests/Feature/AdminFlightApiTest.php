<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminFlightApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_manage_flights(): void
    {
        $this->getJson('/api/admin/flights')->assertUnauthorized();
    }

    public function test_regular_user_cannot_manage_flights(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/admin/flights')
            ->assertForbidden();
    }

    public function test_admin_can_create_update_list_and_delete_flights(): void
    {
        $admin = User::factory()->create();
        $admin->forceFill(['is_admin' => true])->save();
        $token = $admin->createToken('test')->plainTextToken;
        $headers = ['Authorization' => 'Bearer '.$token];
        $data = [
            'flight_number' => 'VN301',
            'origin' => 'Hà Nội (HAN)',
            'destination' => 'Đà Nẵng (DAD)',
            'departure_time' => '2026-12-01T07:30:00',
            'price' => 1250000,
            'cabin_class' => 'economy',
            'seats_available' => 90,
        ];

        $created = $this->withHeaders($headers)
            ->postJson('/api/admin/flights', $data)
            ->assertCreated()
            ->assertJsonPath('data.flight_number', 'VN301');

        $flightId = $created->json('data.id');

        $this->withHeaders($headers)
            ->getJson('/api/admin/flights')
            ->assertOk()
            ->assertJsonPath('data.0.flight_number', 'VN301');

        $this->withHeaders($headers)
            ->putJson('/api/admin/flights/'.$flightId, [...$data, 'destination' => 'Huế'])
            ->assertOk()
            ->assertJsonPath('data.destination', 'Huế');

        $this->assertDatabaseHas('flights', ['id' => $flightId, 'destination' => 'Huế']);

        $this->withHeaders($headers)
            ->deleteJson('/api/admin/flights/'.$flightId)
            ->assertNoContent();

        $this->assertDatabaseMissing('flights', ['id' => $flightId]);
    }

    public function test_flight_number_may_repeat_only_for_a_different_cabin_class(): void
    {
        $admin = User::factory()->create();
        $admin->forceFill(['is_admin' => true])->save();
        $token = $admin->createToken('test')->plainTextToken;
        $headers = ['Authorization' => 'Bearer '.$token];
        $data = [
            'flight_number' => 'VN401',
            'origin' => 'Hà Nội',
            'destination' => 'Đà Nẵng',
            'departure_time' => '2026-12-01T07:30:00',
            'price' => 1250000,
            'cabin_class' => 'economy',
            'seats_available' => 10,
        ];

        $this->withHeaders($headers)->postJson('/api/admin/flights', $data)->assertCreated();
        $this->withHeaders($headers)
            ->postJson('/api/admin/flights', [...$data, 'cabin_class' => 'business'])
            ->assertCreated();
        $this->withHeaders($headers)
            ->postJson('/api/admin/flights', $data)
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['flight_number']);

        $this->assertDatabaseCount('flights', 2);
    }
}

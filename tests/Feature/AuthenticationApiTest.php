<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_creates_a_regular_user_and_returns_a_short_lived_token(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'SkyWings User',
            'email' => 'traveler@example.com',
            'password' => 'secure-password',
            'password_confirmation' => 'secure-password',
        ]);

        $response
            ->assertCreated()
            ->assertJsonPath('user.name', 'SkyWings User')
            ->assertJsonPath('user.is_admin', false)
            ->assertJsonStructure(['token', 'user' => ['id', 'name', 'email', 'is_admin']]);

        $this->assertDatabaseHas('users', ['email' => 'traveler@example.com']);
        $this->assertDatabaseHas('personal_access_tokens', ['name' => 'skywings-web']);
        $this->assertTrue(Hash::check('secure-password', User::where('email', 'traveler@example.com')->firstOrFail()->password));
    }

    public function test_login_returns_a_token_and_logout_revokes_it(): void
    {
        User::factory()->create([
            'email' => 'traveler@example.com',
            'password' => 'secure-password',
        ]);

        $login = $this->postJson('/api/login', [
            'email' => 'traveler@example.com',
            'password' => 'secure-password',
        ])->assertOk();

        $token = $login->json('token');

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/user')
            ->assertOk()
            ->assertJsonPath('data.email', 'traveler@example.com');

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/logout')
            ->assertOk();

        $this->assertDatabaseCount('personal_access_tokens', 0);

        Auth::forgetGuards();
        $this->flushHeaders();
        $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/user')
            ->assertUnauthorized();
    }

    public function test_registration_rejects_an_existing_email_and_password_mismatch(): void
    {
        User::factory()->create(['email' => 'traveler@example.com']);

        $this->postJson('/api/register', [
            'name' => 'Another Traveler',
            'email' => 'traveler@example.com',
            'password' => 'secure-password',
            'password_confirmation' => 'different-password',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['email', 'password']);

        $this->assertDatabaseCount('users', 1);
    }

    public function test_login_rejects_incorrect_credentials(): void
    {
        User::factory()->create([
            'email' => 'traveler@example.com',
            'password' => 'secure-password',
        ]);

        $this->postJson('/api/login', [
            'email' => 'traveler@example.com',
            'password' => 'incorrect-password',
        ])
            ->assertUnprocessable()
            ->assertJsonPath('message', 'The provided credentials are incorrect.');
    }
}

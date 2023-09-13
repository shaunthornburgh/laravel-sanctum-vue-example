<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    /** @test */
    public function test_a_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs(
            $user,
            ['*']
        );

        $this->postJson(route('auth.login'), [
                'email' => $user->email,
                'password' => 'password'
            ],
        )
            ->assertStatus(200);
    }

    /** @test */
    public function test_a_user_cannot_login_with_invalid_credentials(): void
    {
        $user = User::factory()->create();

        $response = $this->post(route('auth.login'), [
            'email' => $user->email,
            'password' => 'WRONG-PASSWORD'
        ]);

        $response->assertStatus(401);
    }

    /** @test */
    public function email_field_is_required(): void
    {
        User::factory()->create();

        $this->postJson(route('auth.login'), [
            'email' => null,
            'password' => 'Password1!'
        ])
            ->assertJsonValidationErrors([
                'email' => 'The email field is required.'
            ])
            ->assertStatus(422);
    }

    /** @test */
    public function email_value_must_be_a_valid_email_address(): void
    {
        User::factory()->create();

        $response = $this->postJson(route('auth.login'), [
            'email' => 'INVALID_EMAIL_ADDRESS',
            'password' => 'password'
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'email' => 'The email field must be a valid email address.'
            ]);
    }

    /** @test */
    public function password_field_is_required(): void
    {
        $user = User::factory()->create();

        $this->postJson(route('auth.login'), [
            'email' => $user->email,
            'password' => null
        ])
            ->assertJsonValidationErrors([
                'password' => 'The password field is required.'
            ])
            ->assertStatus(422);
    }

    /** @test */
    public function test_authenticated_users_can_logout(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs(
            $user,
            ['*']
        );

        $this->deleteJson(
            route('auth.logout'),
            [],
            ['Referer' => env('APP_URL')]
        )
            ->assertOk()
            ->assertJson([
                    'message' => 'Logged out'
                ]);
    }

    /** @test */
    public function unauthenticated_users_cannot_logout(): void
    {
        $this->deleteJson(route('auth.logout'))
            ->assertStatus(401)
            ->assertJson([
                    'message' => 'Unauthenticated.'
                ]);
    }
}

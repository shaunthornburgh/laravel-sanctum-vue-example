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
    public function it_provides_token_to_a_valid_user(): void
    {
        $user = User::factory()->create();

        $this->postJson(route('auth.login'), [
            'email' => $user->email,
            'password' => 'password'
        ])
            ->assertStatus(201)
            ->assertSeeText('token');
    }

    /** @test */
    public function it_does_not_provide_token_with_wrong_credentials(): void
    {
        $user = User::factory()->create();

        $response = $this->post(route('auth.login'), [
            'email' => $user->email,
            'password' => 'WRONG-PASSWORD'
        ]);

        $response->assertStatus(401);
        $response->assertSeeText('Invalid Credentials');
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
    public function authenticated_users_can_logout(): void
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        $this->deleteJson(route('auth.logout'))
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

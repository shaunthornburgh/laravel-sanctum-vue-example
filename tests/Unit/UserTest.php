<?php

namespace Tests\Unit;

use App\Models\Address;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test  */
    public function it_has_expected_columns()
    {
        $this->assertTrue(
            Schema::hasColumns('users', [
                'id',
                'name',
                'email',
                'email_verified_at',
                'password',
            ])
        );
    }
}

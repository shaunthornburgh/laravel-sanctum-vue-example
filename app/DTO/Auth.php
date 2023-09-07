<?php

namespace App\DTO;

use Illuminate\Support\Collection;

class Auth
{
    public function __construct(
        private string $email,
        private string $password
    ) {
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}

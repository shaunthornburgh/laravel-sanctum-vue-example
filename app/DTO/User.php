<?php

namespace App\DTO;

use Illuminate\Support\Collection;

class User
{
    public function __construct(
        private string $name,
        private string $email,
        private string $password
    ) {
    }

    public function getName(): string
    {
        return $this->name;
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

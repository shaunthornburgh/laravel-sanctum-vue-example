<?php

namespace App\DTO;

use Illuminate\Support\Collection;

class PasswordReset
{
    public function __construct(
        private string $email
    ) {
    }

    public function getEmail(): string
    {
        return $this->email;
    }

}

<?php

declare(strict_types=1);

namespace App\Controller\Request;

class RegisterUserRequest
{
    public function __construct(
        public string $username,
        public string $email,
        public string $password
    )
    {
    }
}

<?php

declare(strict_types=1);

namespace App\Controller\Request;

class CreateSharee
{
    public function __construct(
        public bool $readonly,
        public int $shoppingListId,
        public ?string $email = null,
        public ?string $username = null,
    )
    {
    }
}

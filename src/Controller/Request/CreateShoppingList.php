<?php

declare(strict_types=1);

namespace App\Controller\Request;

class CreateShoppingList
{

    public function __construct(
        public string $name,
        public bool $fulfilled,
    )
    {
    }
}

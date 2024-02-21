<?php

declare(strict_types=1);

namespace App\Controller\Request;

class PatchShoppingList
{
    public function __construct(
        public string $name,
        public bool $fulfilled,
        public string $shoppingListId
    )
    {
    }
}

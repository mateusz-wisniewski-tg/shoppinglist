<?php

declare(strict_types=1);

namespace App\Controller\Request;

class CreateShoppingListItem
{
    public function __construct(
        public string $name,
        public int $quantity,
        public string $quantityUnit,
        public bool $purchased,
        public string $shoppingListId
    )
    {
    }
}

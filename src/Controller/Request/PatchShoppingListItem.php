<?php

declare(strict_types=1);

namespace App\Controller\Request;

class PatchShoppingListItem
{
    public function __construct(
        public string $name,
        public bool $purchased,
        public int $quantity,
        public string $quantityUnit,
        public string $shoppingListItemId
    )
    {
    }
}

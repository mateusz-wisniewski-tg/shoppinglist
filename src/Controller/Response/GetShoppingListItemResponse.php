<?php

declare(strict_types=1);

namespace App\Controller\Response;

class GetShoppingListItemResponse
{
    public function __construct(
        public int $id,
        public string $name,
        public int $quantity,
        public string $quantityUnit,
        public bool $purchased,
    )
    {
    }
}

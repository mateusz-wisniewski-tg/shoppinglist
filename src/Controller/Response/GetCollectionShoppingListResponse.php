<?php

declare(strict_types=1);

namespace App\Controller\Response;

class GetCollectionShoppingListResponse
{
    public function __construct(
        public int $id,
        public string $name,
        public bool $fulfilled,
        public array $shoppingListItems,
    )
    {
    }
}

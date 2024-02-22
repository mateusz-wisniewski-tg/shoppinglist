<?php

declare(strict_types=1);

namespace App\Controller\Response;

class GetCollectionShareeResponse
{
    public function __construct(
        public int $shareeId,
        public string $shareeUsername,
        public string $shareeEmail,
        /**
         * @var int[] $shareeShoppingListIds
         */
        public array $shareeShoppingListIds,
    )
    {
    }
}

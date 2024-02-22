<?php

declare(strict_types=1);

namespace App\Controller\Response;

class GetCollectionShoppingListResponseBounded
{
    public function __construct(
        /**
         * @var GetCollectionShoppingListResponse[] $userLists
         */
        public array $userLists,
        /**
         * @var  GetCollectionShoppingListResponse[] $sharedLists
         */
        public array $sharedLists,
    )
    {
    }
}

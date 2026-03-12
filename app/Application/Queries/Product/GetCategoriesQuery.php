<?php

namespace App\Application\Queries\Product;

class GetCategoriesQuery
{
    public function __construct(
        public readonly ?bool $active = true,
        public readonly ?int $parentId = null
    ) {}
}
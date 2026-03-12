<?php
namespace App\Domain\Product\Repositories;
use App\Domain\Product\Entities\Product;

interface ProductRepositoryInterface
{
    public function findById(int $id): ?Product;
    public function findAll(?string $search = null, ?int $categoryId = null, ?int $brandId = null, ?bool $active = null, ?bool $inStock = null, int $page = 1, int $perPage = 15): array;
    public function save(Product $product): Product;
    public function delete(int $id): bool;
}

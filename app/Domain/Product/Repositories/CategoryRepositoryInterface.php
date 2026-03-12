<?php
namespace App\Domain\Product\Repositories;

interface CategoryRepositoryInterface
{
    public function findAll(?bool $active = null): array;
    public function findById(int $id): ?object;
}

<?php
namespace App\Domain\Sale\Repositories;

interface SaleRepositoryInterface
{
    public function findAll(int $page = 1, int $perPage = 15): array;
    public function findById(int $id): ?object;
    public function save(object $sale): object;
}

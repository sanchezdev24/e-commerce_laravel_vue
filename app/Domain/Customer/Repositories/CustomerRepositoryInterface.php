<?php
namespace App\Domain\Customer\Repositories;
use App\Domain\Customer\Entities\Customer;

interface CustomerRepositoryInterface
{
    public function findById(int $id): ?Customer;
    public function findAll(?string $search = null, ?string $type = null, ?bool $active = null, int $page = 1, int $perPage = 15): array;
    public function save(Customer $customer): Customer;
    public function delete(int $id): bool;
}

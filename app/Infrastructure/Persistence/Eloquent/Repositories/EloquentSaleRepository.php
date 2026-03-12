<?php
namespace App\Infrastructure\Persistence\Eloquent\Repositories;

use App\Domain\Sale\Repositories\SaleRepositoryInterface;
use App\Infrastructure\Persistence\Eloquent\Models\Sale;

class EloquentSaleRepository implements SaleRepositoryInterface
{
    public function __construct(private Sale $model) {}

    public function findAll(int $page = 1, int $perPage = 15): array
    {
        $total = $this->model->count();
        $sales = $this->model->with(['customer', 'items'])
            ->latest()
            ->offset(($page - 1) * $perPage)
            ->limit($perPage)
            ->get();
        return [
            'data' => $sales->toArray(),
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => ceil($total / $perPage),
        ];
    }

    public function findById(int $id): ?object
    {
        return $this->model->with(['customer', 'items'])->find($id);
    }

    public function save(object $sale): object
    {
        return $sale; // stub
    }
}

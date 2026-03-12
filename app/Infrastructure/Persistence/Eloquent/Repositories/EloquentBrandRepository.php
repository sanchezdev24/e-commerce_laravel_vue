<?php
namespace App\Infrastructure\Persistence\Eloquent\Repositories;

use App\Domain\Product\Repositories\BrandRepositoryInterface;
use App\Infrastructure\Persistence\Eloquent\Models\Brand;

class EloquentBrandRepository implements BrandRepositoryInterface
{
    public function __construct(private Brand $model) {}

    public function findAll(?bool $active = null): array
    {
        $query = $this->model->newQuery();
        if ($active !== null) {
            $query->where('is_active', $active);
        }
        return $query->get()->toArray();
    }

    public function findById(int $id): ?object
    {
        return $this->model->find($id);
    }
}

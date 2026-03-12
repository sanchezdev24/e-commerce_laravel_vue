<?php
namespace App\Infrastructure\Persistence\Eloquent\Repositories;

use App\Domain\Product\Repositories\CategoryRepositoryInterface;
use App\Infrastructure\Persistence\Eloquent\Models\Category;

class EloquentCategoryRepository implements CategoryRepositoryInterface
{
    public function __construct(private Category $model) {}

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

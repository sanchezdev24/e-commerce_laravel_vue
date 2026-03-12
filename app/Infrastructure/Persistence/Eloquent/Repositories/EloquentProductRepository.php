<?php

namespace App\Infrastructure\Persistence\Eloquent\Repositories;

use App\Domain\Product\Entities\Product as DomainProduct;
use App\Domain\Product\Repositories\ProductRepositoryInterface;
use App\Domain\Product\ValueObjects\Price;
use App\Domain\Product\ValueObjects\Stock;
use App\Domain\Product\ValueObjects\Discount;
use App\Infrastructure\Persistence\Eloquent\Models\Product as EloquentProduct;

class EloquentProductRepository implements ProductRepositoryInterface
{
    public function __construct(
        private EloquentProduct $model
    ) {}

    public function findById(int $id): ?DomainProduct
    {
        $product = $this->model->with(['category', 'brand'])->find($id);
        if (!$product) {
            return null;
        }
        return $this->toDomainEntity($product);
    }

    // Query-side: returns plain arrays (serializable)
    public function findAll(
        ?string $search = null,
        ?int $categoryId = null,
        ?int $brandId = null,
        ?bool $active = null,
        ?bool $inStock = null,
        int $page = 1,
        int $perPage = 15
    ): array {
        $query = $this->model->with(['category', 'brand']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        if ($categoryId) $query->where('category_id', $categoryId);
        if ($brandId)    $query->where('brand_id', $brandId);
        if ($active !== null) $query->where('is_active', $active);
        if ($inStock)    $query->where('stock', '>', 0);

        $total    = $query->count();
        $products = $query->offset(($page - 1) * $perPage)->limit($perPage)->get();

        return [
            'data'        => $products->map(fn($p) => $this->toArray($p))->values()->toArray(),
            'total'       => $total,
            'page'        => $page,
            'per_page'    => $perPage,
            'total_pages' => (int) ceil($total / $perPage),
        ];
    }

    public function save(DomainProduct $product): DomainProduct
    {
        $discount = $product->getDiscount();

        $eloquentProduct = $this->model->updateOrCreate(
            ['id' => $product->getId()],
            [
                'name'                 => $product->getName(),
                'description'          => $product->getDescription(),
                'sku'                  => $product->getSku(),
                'price'                => $product->getPrice()->getValue(),
                'stock'                => $product->getStock()->getQuantity(),
                'min_stock'            => $product->getStock()->getMinQuantity(),
                'category_id'          => $product->getCategoryId(),
                'brand_id'             => $product->getBrandId(),
                'images'               => $product->getImages(),
                'discount_percentage'  => $discount ? $discount->getPercentage() : null,
                'discount_valid_until' => $discount ? $discount->getValidUntil() : null,
                'is_active'            => $product->isActive(),
            ]
        );

        return $this->toDomainEntity($eloquentProduct);
    }

    public function delete(int $id): bool
    {
        return $this->model->destroy($id) > 0;
    }

    private function toArray(EloquentProduct $product): array
    {
        return [
            'id'                   => $product->id,
            'name'                 => $product->name,
            'description'          => $product->description,
            'sku'                  => $product->sku,
            'price'                => (float) $product->price,
            'final_price'          => (float) $product->final_price,
            'stock'                => $product->stock,
            'min_stock'            => $product->min_stock,
            'category_id'          => $product->category_id,
            'brand_id'             => $product->brand_id,
            'category'             => $product->category?->toArray(),
            'brand'                => $product->brand?->toArray(),
            'images'               => $product->images ?? [],
            'discount_percentage'  => $product->discount_percentage,
            'discount_valid_until' => $product->discount_valid_until,
            'is_active'            => $product->is_active,
            'in_stock'             => $product->isInStock(),
            'is_low_stock'         => $product->isLowStock(),
            'created_at'           => $product->created_at,
            'updated_at'           => $product->updated_at,
        ];
    }

    private function toDomainEntity(EloquentProduct $product): DomainProduct
    {
        $price    = new Price($product->price);
        $stock    = new Stock($product->stock, $product->min_stock);
        $discount = null;

        if ($product->discount_percentage) {
            $discount = new Discount(
                $product->discount_percentage,
                $product->discount_valid_until
            );
        }

        return new DomainProduct(
            $product->id,
            $product->name,
            $product->description,
            $product->sku,
            $price,
            $stock,
            $product->category_id,
            $product->brand_id,
            $product->images ?? [],
            $discount,
            $product->is_active,
            $product->created_at,
            $product->updated_at
        );
    }
}

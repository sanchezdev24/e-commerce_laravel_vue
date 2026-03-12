<?php
namespace App\Application\Handlers\Queries\Product;

use App\Application\Queries\Product\GetProductByIdQuery;
use App\Domain\Product\Repositories\ProductRepositoryInterface;

class GetProductByIdQueryHandler
{
    public function __construct(private ProductRepositoryInterface $productRepository) {}

    public function handle(GetProductByIdQuery $query): ?array
    {
        $product = $this->productRepository->findById($query->id);
        if (!$product) return null;

        // Convert domain entity to array
        return [
            'id'                   => $product->getId(),
            'name'                 => $product->getName(),
            'description'          => $product->getDescription(),
            'sku'                  => $product->getSku(),
            'price'                => $product->getPrice()->getValue(),
            'final_price'          => $product->getFinalPrice(),
            'stock'                => $product->getStock()->getQuantity(),
            'min_stock'            => $product->getStock()->getMinQuantity(),
            'category_id'          => $product->getCategoryId(),
            'brand_id'             => $product->getBrandId(),
            'images'               => $product->getImages(),
            'is_active'            => $product->isActive(),
            'in_stock'             => $product->isInStock(),
        ];
    }
}

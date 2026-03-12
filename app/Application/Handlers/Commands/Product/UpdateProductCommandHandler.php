<?php
namespace App\Application\Handlers\Commands\Product;

use App\Application\Commands\Product\UpdateProductCommand;
use App\Domain\Product\Repositories\ProductRepositoryInterface;
use App\Domain\Product\Entities\Product;
use App\Domain\Product\ValueObjects\Price;
use App\Domain\Product\ValueObjects\Stock;
use App\Domain\Product\ValueObjects\Discount;

class UpdateProductCommandHandler
{
    public function __construct(private ProductRepositoryInterface $productRepository) {}

    public function handle(UpdateProductCommand $command): array
    {
        $existing = $this->productRepository->findById($command->id);
        if (!$existing) throw new \Exception("Product not found");

        $discount = null;
        if ($command->discountPercentage !== null) {
            $validUntil = $command->discountValidUntil ? new \DateTime($command->discountValidUntil) : null;
            $discount = new Discount($command->discountPercentage, $validUntil);
        }

        $product = new Product(
            $command->id,
            $command->name,
            $command->description,
            $command->sku,
            new Price($command->price),
            new Stock($command->stock),
            $command->categoryId,
            $command->brandId,
            $command->images,
            $discount,
            $existing->isActive(),
        );

        $saved = $this->productRepository->save($product);

        return [
            'id'          => $saved->getId(),
            'name'        => $saved->getName(),
            'description' => $saved->getDescription(),
            'sku'         => $saved->getSku(),
            'price'       => $saved->getPrice()->getValue(),
            'final_price' => $saved->getFinalPrice(),
            'stock'       => $saved->getStock()->getQuantity(),
            'category_id' => $saved->getCategoryId(),
            'brand_id'    => $saved->getBrandId(),
            'images'      => $saved->getImages(),
            'is_active'   => $saved->isActive(),
            'in_stock'    => $saved->isInStock(),
        ];
    }
}

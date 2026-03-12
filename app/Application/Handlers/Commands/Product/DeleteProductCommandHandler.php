<?php
namespace App\Application\Handlers\Commands\Product;

use App\Application\Commands\Product\DeleteProductCommand;
use App\Domain\Product\Repositories\ProductRepositoryInterface;

class DeleteProductCommandHandler
{
    public function __construct(private ProductRepositoryInterface $productRepository) {}

    public function handle(DeleteProductCommand $command): bool
    {
        $product = $this->productRepository->findById($command->id);
        if (!$product) {
            throw new \Exception("Product not found");
        }
        return $this->productRepository->delete($command->id);
    }
}

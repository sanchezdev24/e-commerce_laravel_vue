<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder; 
use App\Infrastructure\Persistence\Eloquent\Models\Product;
use App\Infrastructure\Persistence\Eloquent\Models\Category;
use App\Infrastructure\Persistence\Eloquent\Models\Brand;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Asegurarse de que hay categorías y marcas
        $categories = Category::all();
        $brands = Brand::all();

        if ($categories->isEmpty() || $brands->isEmpty()) {
            $this->command->error('Necesitas crear categorías y marcas primero');
            return;
        }

        // Crear productos
        Product::factory(100)->create([
            'category_id' => $categories->random()->id,
            'brand_id' => $brands->random()->id,
        ]);
    }
}
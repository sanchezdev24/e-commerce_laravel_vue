<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Infrastructure\Persistence\Eloquent\Models\Brand;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            ['name' => 'Nike', 'description' => 'Marca deportiva premium'],
            ['name' => 'Adidas', 'description' => 'Ropa y calzado deportivo'],
            ['name' => 'Zara', 'description' => 'Moda contemporánea'],
            ['name' => 'H&M', 'description' => 'Moda rápida y accesible'],
            ['name' => 'Levi\'s', 'description' => 'Jeans y ropa casual'],
        ];

        foreach ($brands as $brand) {
            Brand::create($brand);
        }

        // Crear marcas adicionales
        Brand::factory(10)->create();
    }
}
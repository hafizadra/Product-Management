<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1) Buat minimal 3 kategori (sesuai tugas)
        $categories = collect([
            'Electronics',
            'Fashion',
            'Books',
        ])->map(function (string $name) {
            return Category::create(['name' => $name]);
        });

        // 2) Buat minimal 30 produk (sesuai tugas) TANPA factory, tapi pakai Faker
        $faker = fake(); // helper Faker dari Laravel

        for ($i = 1; $i <= 30; $i++) {
            Product::create([
                'category_id' => Category::inRandomOrder()->value('id'),
                'name'        => $faker->words(3, true),
                'description' => $faker->sentence(10),
                'price'       => $faker->numberBetween(10000, 500000),
            ]);
        }
    }
}

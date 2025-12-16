<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;

class DatabaseSeeder extends Seeder
{

    public function run(): void
    {

        $categories = collect([
            'Electronics',
            'Fashion',
            'Books',
        ])->map(function (string $name) {
            return Category::create([
                'name' => $name,
            ]);
        });

 
        Product::factory()
            ->count(30) 
            ->create();  
    }
}

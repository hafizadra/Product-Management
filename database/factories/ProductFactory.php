<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as FakerFactory;   

class ProductFactory extends Factory
{

    protected $model = Product::class;


    public function definition(): array
    {

        $faker = FakerFactory::create('en_US');

        return [

            'category_id' => Category::inRandomOrder()->value('id'),


            'name' => $faker->words(3, true),

            'description' => $faker->sentence(10),


            'price' => $faker->numberBetween(10000, 1000000),
        ];
    }
}

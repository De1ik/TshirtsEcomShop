<?php

namespace Database\Factories;

use App\Models\Collection;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        $price = $this->faker->randomFloat(2, 1, 100);

        return [
            'collection_id' => Collection::factory(),
            'name'          => $this->faker->word(),
            'description'   => $this->faker->sentence(),
            'price'         => $price,
            'final_price'   => $price,
            'is_discount'   => false,
            'category'      => $this->faker->randomElement(['tshirt', 'hoodie']),
            'gender'        => $this->faker->randomElement(['male', 'female', 'unisex']),
            'created_at'    => now(),
            'updated_at'    => now(),
        ];
    }
}

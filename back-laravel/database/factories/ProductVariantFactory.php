<?php

namespace Database\Factories;

use App\Models\Color;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductVariant>
 */
class ProductVariantFactory extends Factory
{
    protected $model = ProductVariant::class;

    public function definition()
    {
        return [
            'product_id' => Product::factory(),
            'size'       => $this->faker->randomElement(['XS','S','M','L','XL','XXL']),
            'amount'     => $this->faker->numberBetween(1, 20),

            'color_id'   => Color::factory(),

            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CartItem>
 */
class CartItemFactory extends Factory
{
    protected $model = CartItem::class;

    public function definition()
    {
        $variant  = ProductVariant::factory()->create();
        $quantity = $this->faker->numberBetween(1, 5);
        $unit     = $variant->product->final_price;

        return [
            'cart_id'            => Cart::factory(),
            'product_variant_id' => $variant->id,
            'quantity'           => $quantity,
            'unit_price'         => $unit,
            'total_price'        => $unit * $quantity,
        ];
    }
}

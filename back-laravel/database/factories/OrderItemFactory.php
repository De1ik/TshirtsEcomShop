<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderItem>
 */
class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $variant = ProductVariant::factory()->create();

        return [
            'order_id'     => Order::factory(),
            'product_id'   => $variant->product_id,
            'variant_id'   => $variant->id,
            'quantity'     => $this->faker->numberBetween(1, 3),
            'price_by_one' => $variant->product->final_price,
        ];
    }
}

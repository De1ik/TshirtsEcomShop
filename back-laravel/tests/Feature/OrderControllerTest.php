<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\ShippingInfo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_view_own_order()
    {
        $user = User::factory()->create();
        $shipping = ShippingInfo::factory()->create(['user_id' => $user->id]);

        $order = Order::factory()->create(['user_id' => $user->id]);
        $variant = ProductVariant::factory()->create();
        $product = Product::factory()->create();
        ProductImage::factory()->create([
            'product_id' => $product->id,
            'is_main'    => true,
        ]);
        $variant->product()->associate($product)->save();

        OrderItem::factory()->create([
            'order_id' => $order->id,
            'variant_id' => $variant->id,
            'price_by_one' => 100,
            'quantity' => 2,
        ]);

        Payment::factory()->create(['order_id' => $order->id]);

        $response = $this->actingAs($user)->get(route('order.details', $order->id));

        $response->assertStatus(200);
        $response->assertViewIs('order.submitted_order');
        $response->assertViewHas(['order', 'shipping', 'subtotal', 'discount', 'payment']);
    }

    public function test_guest_user_can_view_order_using_session_email()
    {
        $guestEmail = 'guest@example.com';
        $user = User::factory()->create(['email' => $guestEmail]);
        $shipping = ShippingInfo::factory()->create(['user_id' => $user->id]);

        $order = Order::factory()->create(['user_id' => $user->id]);
        $variant = ProductVariant::factory()->create();
        $product = Product::factory()->create();
        ProductImage::factory()->create([
            'product_id' => $product->id,
            'is_main'    => true,
        ]);
        $variant->product()->associate($product)->save();

        OrderItem::factory()->create([
            'order_id' => $order->id,
            'variant_id' => $variant->id,
            'price_by_one' => 100,
            'quantity' => 2,
        ]);

        Payment::factory()->create(['order_id' => $order->id]);

        $response = $this->withSession(['guest_email' => $guestEmail])
            ->get(route('order.details', $order->id));

        $response->assertStatus(200);
        $response->assertViewIs('order.submitted_order');
        $response->assertViewHas(['order', 'shipping', 'subtotal', 'discount', 'payment']);
    }

    public function test_user_cannot_view_someone_elses_order()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $order = Order::factory()->create(['user_id' => $user2->id]);

        $response = $this->actingAs($user1)->get(route('order.details', $order->id));

        $response->assertStatus(404);
    }
}

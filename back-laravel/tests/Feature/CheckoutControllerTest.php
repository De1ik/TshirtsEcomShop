<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\CartItem;
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

class CheckoutControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_checkout_successfully()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $product = Product::factory()->create(['price' => 100, 'final_price' => 100]);
        ProductImage::factory()->create(['product_id' => $product->id, 'is_main' => true]);

        $variant = ProductVariant::factory()->create([
            'product_id' => $product->id,
            'amount' => 10
        ]);

        $cart = Cart::factory()->create(['user_id' => $user->id]);

        CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_variant_id' => $variant->id,
            'quantity' => 2,
            'unit_price' => 100,
            'total_price' => 200,
        ]);

        $response = $this->post(route('checkout.store'), [
            'email' => $user->email,
            'country' => 'Slovakia',
            'city' => 'Bratislava',
            'address' => 'Main Street 1',
            'postcode' => '12345',
            'phone' => '+421900000000',
            'payment_method' => 'cash',
        ]);

        $response->assertRedirectContains('/order/');

        $this->assertDatabaseHas(Order::class, ['user_id' => $user->id]);
        $this->assertDatabaseHas(OrderItem::class, ['variant_id' => $variant->id]);
        $this->assertDatabaseHas(ShippingInfo::class, ['user_id' => $user->id, 'city' => 'Bratislava']);
        $this->assertDatabaseHas(Payment::class, ['payment_method' => 'cash', 'payment_status' => 'pending']);
    }

    public function test_guest_user_can_checkout_successfully()
    {
        $guestEmail = 'guest@example.com';

        $product = Product::factory()->create(['price' => 80, 'final_price' => 80]);
        ProductImage::factory()->create(['product_id' => $product->id, 'is_main' => true]);

        $variant = ProductVariant::factory()->create([
            'product_id' => $product->id,
            'amount' => 5
        ]);

        $cartItem = [
            'variant_id' => $variant->id,
            'quantity' => 2,
            'unit_price' => 80,
        ];

        session()->put('cart', [$cartItem]);

        $response = $this->post(route('checkout.store'), [
            'email' => $guestEmail,
            'country' => 'Slovakia',
            'city' => 'Bratislava',
            'address' => 'Freedom Square 5',
            'postcode' => '04001',
            'phone' => '+421911000111',
            'payment_method' => 'google_pay',
        ]);

        $response->assertRedirectContains('/order/');

        $guestUser = User::where('email', $guestEmail)->first();
        $this->assertNotNull($guestUser);
        $this->assertEquals('guest', $guestUser->role->value);

        $this->assertDatabaseHas(Order::class, ['user_id' => $guestUser->id]);
        $this->assertDatabaseHas(OrderItem::class, ['variant_id' => $variant->id]);
        $this->assertDatabaseHas(ShippingInfo::class, ['user_id' => $guestUser->id, 'city' => 'Bratislava']);
        $this->assertDatabaseHas(Payment::class, ['payment_method' => 'google_pay', 'payment_status' => 'paid']);
    }

    public function test_checkout_fails_with_invalid_data()
    {
        $invalidData = [
            'email' => 'invalid-email',
            'country' => '',
            'city' => '',
            'address' => '',
            'postcode' => 'abc',
            'phone' => 'not-a-phone',
            'payment_method' => 'bitcoin',
        ];

        $response = $this->from(route('checkout'))->post(route('checkout.store'), $invalidData);

        $response->assertRedirect(route('checkout'));
        $response->assertSessionHasErrors([
            'email',
            'country',
            'city',
            'address',
            'postcode',
            'phone',
            'payment_method',
        ]);

        $this->assertDatabaseCount(Order::class, 0);
    }

}

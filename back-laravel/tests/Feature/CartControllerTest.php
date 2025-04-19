<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class CartControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_cart_page_shows_for_authenticated_user()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Cart::factory()->create(['user_id' => $user->id]);

        $response = $this->get(route('cart.index'));
        $response->assertStatus(200);
        $response->assertViewIs('order.cart');
    }

    public function test_increase_quantity_of_cart_item()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $variant = ProductVariant::factory()->create();
        $cart = Cart::factory()->create(['user_id' => $user->id]);
        $item = CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_variant_id' => $variant->id,
            'quantity' => 1,
            'unit_price' => 10,
            'total_price' => 10,
        ]);

        $response = $this->post(route('cart.increase', $item->id));

        $response->assertRedirect();
        $this->assertDatabaseHas('cart_items', [
            'id' => $item->id,
            'quantity' => 2,
            'total_price' => 20,
        ]);
    }

    public function test_decrease_quantity_removes_item_if_quantity_is_one()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $variant = ProductVariant::factory()->create();
        $cart = Cart::factory()->create(['user_id' => $user->id]);
        $item = CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_variant_id' => $variant->id,
            'quantity' => 1,
            'unit_price' => 15,
            'total_price' => 15,
        ]);

        $response = $this->post(route('cart.decrease', $item->id));

        $response->assertRedirect();
        $this->assertDatabaseMissing('cart_items', ['id' => $item->id]);
    }

    public function test_remove_cart_item()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $variant = ProductVariant::factory()->create();
        $cart = Cart::factory()->create(['user_id' => $user->id]);
        $item = CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_variant_id' => $variant->id,
        ]);

        $response = $this->post(route('cart.remove', $item->id));

        $response->assertRedirect();
        $this->assertDatabaseMissing('cart_items', ['id' => $item->id]);
    }

    public function test_cart_page_shows_for_guest_user_with_session_cart()
    {
        $cart = [
            [
                'variant_id' => 1,
                'quantity' => 2,
                'unit_price' => 10,
                'total_price' => 20,
            ]
        ];

        Session::put('cart', $cart);

        $response = $this->get(route('cart.index'));

        $response->assertStatus(200);
        $response->assertViewIs('order.cart');
        $response->assertViewHas('cart', $cart);
    }

    public function test_guest_session_cart_can_be_saved_and_read()
    {
        $cartItem = [
            'variant_id' => 5,
            'quantity' => 3,
            'unit_price' => 10,
            'total_price' => 30,
        ];

        session()->put('cart', [$cartItem]);

        $this->assertEquals(session('cart')[0]['variant_id'], 5);
        $this->assertEquals(session('cart')[0]['quantity'], 3);
    }

    public function test_increase_quantity_fails_for_invalid_item()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('cart.increase', 999)); // Non-existing ID

        $response->assertStatus(404);
    }

    public function test_decrease_quantity_fails_for_invalid_item()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('cart.decrease', 999)); // Non-existing ID

        $response->assertStatus(404);
    }

    public function test_remove_item_fails_for_invalid_id()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('cart.remove', 999)); // Non-existing ID

        $response->assertStatus(404);
    }

    public function test_session_cart_merges_into_database_cart()
    {
        $user = User::factory()->create();

        $variant = ProductVariant::factory()->create();
        session()->put('cart', [
            [
                'variant_id' => $variant->id,
                'quantity' => 2,
                'unit_price' => 10,
                'total_price' => 20,
            ]
        ]);

        $this->actingAs($user);
        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertEmpty(session('cart', []));
        $this->assertDatabaseHas('cart_items', [
            'product_variant_id' => $variant->id,
            'quantity' => 2,
        ]);
    }
}

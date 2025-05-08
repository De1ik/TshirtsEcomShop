<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class CartControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_cart_page_shows_for_authenticated_user()
    {
        view()->share('latestCollection', (object)[
            'id'   => 0,
            'name' => '',
        ]);

        $user = User::factory()->create();
        $this->actingAs($user);

        Cart::factory()->create(['user_id' => $user->id]);

        $response = $this->get(route('cart'));
        $response->assertStatus(200);
        $response->assertViewIs('order.cart');
    }

    /** @test */
    public function test_increase_quantity_of_cart_item()
    {
        view()->share('latestCollection', collect());

        $user = User::factory()->create();
        $this->actingAs($user);

        $variant = ProductVariant::factory()->create();
        $cart    = Cart::factory()->create(['user_id' => $user->id]);
        $item    = CartItem::factory()->create([
            'cart_id'            => $cart->id,
            'product_variant_id' => $variant->id,
            'quantity'           => 1,
            'unit_price'         => 10,
            'total_price'        => 10,
        ]);

        $response = $this->patch(route('cart.increase', $item->id));

        $response->assertRedirect();
        $this->assertDatabaseHas('cart_items', [
            'id'          => $item->id,
            'quantity'    => 2,
            'total_price' => 20,
        ]);
    }

    /** @test */
    public function test_decrease_quantity_removes_item_if_quantity_is_one()
    {
        view()->share('latestCollection', collect());

        $user = User::factory()->create();
        $this->actingAs($user);

        $variant = ProductVariant::factory()->create();
        $cart    = Cart::factory()->create(['user_id' => $user->id]);
        $item    = CartItem::factory()->create([
            'cart_id'            => $cart->id,
            'product_variant_id' => $variant->id,
            'quantity'           => 1,
            'unit_price'         => 15,
            'total_price'        => 15,
        ]);

        $response = $this->patch(route('cart.decrease', $item->id));

        $response->assertRedirect();
        $this->assertDatabaseMissing('cart_items', ['id' => $item->id]);
    }


    public function test_remove_cart_item()
    {
        view()->share('latestCollection', collect());

        $user = User::factory()->create();
        $this->actingAs($user);

        $variant = ProductVariant::factory()->create();
        $cart = Cart::factory()->create(['user_id' => $user->id]);
        $item = CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_variant_id' => $variant->id,
        ]);

        $response = $this->delete(route('cart.remove', $item->id));

        $response->assertRedirect();
        $this->assertDatabaseMissing('cart_items', ['id' => $item->id]);
    }

    /** @test */
    public function test_cart_page_shows_for_guest_user_with_session_cart()
    {
        view()->share('latestCollection', (object)[
            'id'   => 0,
            'name' => '',
        ]);

        $variant = ProductVariant::factory()->create([
            'amount' => 5,
        ]);

        $sessionCart = [
            [
                'variant_id'  => $variant->id,
                'quantity'    => 2,
                'unit_price'  => 10,
                'total_price' => 20,
            ],
        ];
        session(['cart' => $sessionCart]);

        $response = $this->get(route('cart'));

        $response
            ->assertStatus(200)
            ->assertViewIs('order.cart')
            ->assertViewHas('cart');

        /** @var \stdClass $viewCart */
        $viewCart = $response->viewData('cart');

        $this->assertCount(1, $viewCart->items);

        $item = $viewCart->items->first();
        $this->assertEquals($variant->id, $item->id);
        $this->assertEquals(2,            $item->quantity);
        $this->assertEquals(10,           $item->unit_price);
        $this->assertEquals(20,           $item->total_price);
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

        $response = $this->patch(route('cart.increase', 999999)); // Non-existing ID

        $response->assertStatus(404);
    }

    public function test_decrease_quantity_fails_for_invalid_item()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->patch(route('cart.decrease', 999999)); // Non-existing ID

        $response->assertStatus(404);
    }

    public function test_remove_item_fails_for_invalid_id()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->delete(route('cart.remove', 999999)); // Non-existing ID

        $response->assertStatus(404);
    }

    /** @test */
    public function test_session_cart_merges_into_database_cart()
    {
        view()->share('latestCollection', (object)[
            'id'   => 0,
            'name' => '',
        ]);

        $user = User::factory()->create([
            'password_hash' => Hash::make('password123'),
        ]);

        $variant = ProductVariant::factory()->create();

        session()->put('cart', [
            [
                'variant_id'  => $variant->id,
                'quantity'    => 2,
                'unit_price'  => 10,
                'total_price' => 20,
            ],
        ]);

        $response = $this->post('/login', [
            'email'    => $user->email,
            'password' => 'password123',
        ]);

        $response->assertRedirect('/');

        $this->assertEmpty(session('cart', []));

        $this->assertDatabaseHas('cart_items', [
            'product_variant_id' => $variant->id,
            'quantity'           => 2,
        ]);
    }
}

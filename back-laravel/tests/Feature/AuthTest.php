<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_register()
    {
        $response = $this->post('/register', [
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/login');
        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
    }

    /** @test */
    public function registration_fails_with_invalid_data()
    {
        $response = $this->post('/register', [
            'email' => 'not-an-email',
            'password' => 'short',
            'password_confirmation' => 'mismatch',
        ]);

        $response->assertSessionHasErrors(['email', 'password']);
        $this->assertDatabaseMissing('users', ['email' => 'not-an-email']);
    }

    /** @test */
    public function user_can_login_with_correct_credentials()
    {
        $user = User::create([
            'email' => 'test@example.com',
            'password_hash' => Hash::make('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function user_cannot_login_with_invalid_password()
    {
        $user = User::create([
            'email' => 'test@example.com',
            'password_hash' => Hash::make('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /** @test */
    public function authenticated_user_can_logout()
    {
        $user = User::create([
            'email' => 'test@example.com',
            'password_hash' => Hash::make('password123'),
        ]);

        $this->be($user);

        $response = $this->post('/logout');

        $response->assertRedirect('/');
        $this->assertGuest();
    }

    /** @test */
    public function registration_fails_if_email_is_taken()
    {
        User::factory()->create(['email' => 'test@example.com']);

        $response = $this->post('/register', [
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertEquals(1, User::where('email', 'test@example.com')->count());
    }

    /** @test */
    public function user_cannot_login_with_non_existing_email()
    {
        $response = $this->post('/login', [
            'email' => 'nouser@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /** @test */
    public function user_is_redirected_to_intended_url_after_login()
    {
        $user = User::create([
            'email' => 'test@example.com',
            'password_hash' => Hash::make('password123'),
        ]);

        $this->withSession(['url.intended' => '/profile']);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/');
    }

    /** @test */
    public function it_merges_session_cart_to_database_cart_on_login()
    {
        $product = Product::factory()->create();
        $variant = ProductVariant::factory()->create([
            'product_id' => $product->id,
        ]);

        $sessionCart = [
            [
                'variant_id' => $variant->id,
                'quantity'   => 2,
                'unit_price' => 25,
            ],
        ];

        $user = User::factory()->create([
            'email'         => 'user@example.com',
            'password_hash' => Hash::make('password123'),
        ]);

        $response = $this
            ->withSession(['cart' => $sessionCart])
            ->post('/login', [
                'email'    => 'user@example.com',
                'password' => 'password123',
            ]);

        $response->assertRedirect('/');

        $this->assertDatabaseHas('carts', [
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseHas('cart_items', [
            'product_variant_id' => $variant->id,
            'quantity'           => 2,
            'unit_price'         => 25,
        ]);
    }

    /** @test */
    public function session_cart_is_cleared_after_merge()
    {
        $product = Product::factory()->create();
        $variant = ProductVariant::factory()->create([
            'product_id' => $product->id,
        ]);

        $user = User::factory()->create([
            'email'         => 'test@example.com',
            'password_hash' => Hash::make('password123'),
        ]);

        $response = $this
            ->withSession([
                'cart' => [
                    [
                        'variant_id' => $variant->id,
                        'quantity'   => 1,
                        'unit_price' => 10,
                    ],
                ],
            ])
            ->post('/login', [
                'email'    => 'test@example.com',
                'password' => 'password123',
            ]);

        $response->assertRedirect('/');

        $this->assertFalse(session()->has('cart'));
    }



}

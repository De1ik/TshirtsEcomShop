<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReviewControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_post_review()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($user)->post(route('review.store', $product->id), [
            'rating' => 4,
            'description' => 'Great product!',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('reviews', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'rating' => 4,
            'description' => 'Great product!',
        ]);
    }

    public function test_guest_user_cannot_post_review()
    {
        $product = Product::factory()->create();

        $response = $this->post(route('review.store', $product->id), [
            'rating' => 5,
            'description' => 'Nice!',
        ]);

        $response->assertRedirect(route('login'));
        $this->assertDatabaseMissing('reviews', [
            'product_id' => $product->id,
            'rating' => 5,
        ]);
    }

    public function test_review_requires_valid_data()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($user)->post(route('review.store', $product->id), [
            'rating' => 6, // invalid
            'description' => '', // empty
        ]);

        $response->assertSessionHasErrors(['rating', 'description']);
    }
}

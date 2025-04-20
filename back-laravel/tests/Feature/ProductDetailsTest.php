<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductDetailsTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_details_page_loads()
    {
        $product = Product::factory()->create();

        $response = $this->get(route('product.details', $product->id));
        $response->assertStatus(200);
        $response->assertSee($product->name);
    }

    public function test_nonexistent_product_returns_404()
    {
        $response = $this->get(route('product.details', 999)); // Assuming 999 doesn't exist
        $response->assertStatus(404);
    }
}

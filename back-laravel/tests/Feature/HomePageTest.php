<?php

namespace Tests\Feature;

use App\Models\Collection;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function homepage_loads_successfully_without_collection()
    {
        // No collections in DB
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewIs('index');
        $response->assertViewHasAll([
            'products',
            'last_collection_products',
            'collection_id',
        ]);
    }

    /** @test */
    public function homepage_loads_successfully_with_a_collection()
    {
        $collection = Collection::factory()->create(['release_date' => now()]);

        Product::factory()->create([
            'collection_id' => $collection->id,
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewIs('index');
        $response->assertViewHas('collection_id', $collection->id);
    }
}

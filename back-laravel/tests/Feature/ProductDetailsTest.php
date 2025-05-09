<?php

namespace Tests\Feature;

use App\Http\Controllers\ProductController;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Tests\TestCase;

class ProductDetailsTest extends TestCase
{
    use RefreshDatabase;

    public function test_nonexistent_product_returns_404()
    {
        $response = $this->get(route('product.details', 999999));
        $response->assertStatus(404);
    }

    /** @test */
    public function it_returns_the_correct_view_and_data_for_a_product()
    {
        $product = Product::factory()->create(['category' => 'tshirt']);
        $variant = ProductVariant::factory()->create([
            'product_id' => $product->id,
            'size'       => 'M',
            'amount'     => 3,
        ]);
        $variant->color()->associate(
            Color::factory()->create(['name' => 'Blue'])
        )->save();

        $controller = app(ProductController::class);
        /** @var View $view */
        $view = $controller->show(new Request, $product->id);

        $this->assertInstanceOf(View::class, $view);
        $this->assertEquals('product_details', $view->name());

        $data = $view->getData();
        $this->assertEquals($product->id, $data['product']->id);
        $this->assertEquals('blue',       $data['selectedColor']);
        $this->assertEquals('M',          $data['selectedSize']);
        $this->assertEquals([$variant->size], $data['availableSizes']->values()->all());
        $this->assertEquals($variant->id, $data['selectedVariant']->id);
    }

    /** @test */
    public function it_respects_color_and_size_query_parameters()
    {

        $product = Product::factory()->create(['category' => 'hoodie']);
        $variant = ProductVariant::factory()->create([
            'product_id' => $product->id,
            'size'       => 'L',
            'amount'     => 3,
        ]);
        $variant->color()->associate(
            Color::factory()->create(['name' => 'Green'])
        )->save();

        $request = Request::create(
            route('product.details', ['id' => $product->id]),
            'GET',
            ['color' => 'green', 'size' => 'L']
        );

        $controller = app(ProductController::class);
        /** @var \Illuminate\Contracts\View\View $view */
        $view = $controller->show($request, $product->id);

        $data = $view->getData();

        $this->assertEquals('green', $data['selectedColor']);
        $this->assertEquals('L',     $data['selectedSize']);
        $this->assertEquals(
            $variant->id,
            $data['selectedVariant']->id
        );
    }

    /** @test */
    public function it_sorts_available_sizes_in_defined_order()
    {

        $product = Product::factory()->create(['category' => 'tshirt']);
        $black   = Color::factory()->create(['name' => 'Black']);

        foreach (['M', 'XS', 'XL', 'L'] as $size) {
            $v = ProductVariant::factory()->create([
                'product_id' => $product->id,
                'size'       => $size,
                'amount'     => $size === 'L' ? 0 : 2,
            ]);
            $v->color()->associate($black)->save();
        }

        $response = $this->get(route('product.details', [
            'id'    => $product->id,
            'color' => 'black',
        ]));

        $response
            ->assertOk()
            ->assertViewHas('availableSizes', function ($sizes) {
                return $sizes->values()->all() === ['XS', 'M', 'XL'];
            });
    }



    /** @test */
    public function it_picks_two_similar_products_of_same_category_excluding_self()
    {
        $subject = Product::factory()->create(['category' => 'hoodie']);
        $subjectVariant = ProductVariant::factory()->create([
            'product_id' => $subject->id,
            'size'       => 'M',
            'amount'     => 1,
        ]);
        $subjectVariant->color()->associate(
            Color::factory()->create(['name' => 'Black'])
        )->save();

        $others = Product::factory()->count(3)->create(['category' => 'hoodie']);
        foreach ($others as $other) {
            $v = ProductVariant::factory()->create([
                'product_id' => $other->id,
                'size'       => 'M',
                'amount'     => 1,
            ]);
            $v->color()->associate(Color::factory()->create(['name' => 'Black']))->save();
        }

        $tshirt = Product::factory()->create(['category' => 'tshirt']);
        $v2 = ProductVariant::factory()->create([
            'product_id' => $tshirt->id,
            'size'       => 'M',
            'amount'     => 1,
        ]);
        $v2->color()->associate(Color::factory()->create(['name' => 'Black']))->save();

        $response = $this->get(route('product.details', ['id' => $subject->id]));

        $response
            ->assertOk()
            ->assertViewIs('product_details')
            ->assertViewHas('similarProducts', function ($list) use ($subject) {
                return $list->count() === 2
                    && $list->every(fn($p) => $p->category === 'hoodie')
                    && !$list->contains('id', $subject->id);
            });
    }


}

<?php

namespace Database\Factories;

use App\Models\Collection;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Collection>
 */
class CollectionFactory extends Factory
{
    protected $model = Collection::class;

    public function definition()
    {
        return [
            'name'         => $this->faker->word(),
            'release_date' => $this->faker->optional()->date(),
            'created_at'   => now(),
            'updated_at'   => now(),
        ];
    }
}

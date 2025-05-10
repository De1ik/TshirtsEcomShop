<?php

namespace Database\Factories;

use App\Models\ShippingInfo;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ShippingInfo>
 */
class ShippingInfoFactory extends Factory
{
    protected $model = ShippingInfo::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'phone'   => $this->faker->phoneNumber(),
            'country' => $this->faker->country(),
            'city'    => $this->faker->city(),
            'address' => $this->faker->streetAddress(),
            'postcode'=> $this->faker->postcode(),
        ];
    }
}

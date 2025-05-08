<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'first_name'   => $this->faker->firstName(),
            'last_name'    => $this->faker->lastName(),
            'email'        => $this->faker->unique()->safeEmail(),
            // store in your password_hash column:
            'password_hash'=> bcrypt('password123'),
            'gender'       => $this->faker->randomElement(['male', 'female']),
            'role'         => \App\Enums\Role::USER,
            'created_at'   => now(),
            'updated_at'   => now(),
        ];
    }
}

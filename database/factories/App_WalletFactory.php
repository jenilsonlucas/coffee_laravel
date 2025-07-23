<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class App_WalletFactory extends Factory
{
    /**
     * Define the model's default state.
     *  
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'wallet' => fake()->country(),
            'free' => fake()->boolean()
        ];
    }
}

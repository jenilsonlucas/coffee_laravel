<?php

namespace Database\Factories;

use App\Models\App_Category;
use App\Models\App_Wallet;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AppInvoice>
 */
class AppInvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "user_id" => User::factory(),
            "wallet_id" => App_Wallet::factory(),
            "category_id" => App_Category::factory(),
            "description" => fake()->sentence(),
            "type" => fake()->word(),
            "value" => fake()->numberBetween(100, 1000),
            "due_at" => now(),
            "repeat_when" => fake()->word(5),
        ];
    }
}

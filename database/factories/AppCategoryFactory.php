<?php

namespace Database\Factories;

use App\Models\App_Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class AppCategoryFactory extends Factory
{

    protected $model = App_Category::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "name" => fake()->monthName(),
            "type" => fake()->monthName(),
            "sub_of" => null,
            "order_by" => rand(0, 5)

        ];
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->company();
        return [
            'title' => $title,
            'uri' => Str::slug($title),
            'description' =>fake()->sentence(),
            'cover' => '/assets/images/capa_post.png'
        ];
    }
}

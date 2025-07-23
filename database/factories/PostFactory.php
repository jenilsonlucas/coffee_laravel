<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->company();
        return [
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
            'title' => $name,
            'uri' => Str::slug($name, '-'),
            'subtitle' => fake()->sentence(4),
            'content' => $this->generateHtmlContent(),
            'cover' => '/assets/images/capa_post.png',
            'post_at' => now(),
            'views' => rand(0, 999),

        ];
    }

    private function generateHtmlContent(): string
    {
        $faker = $this->faker;

        return <<<HTML
        <p>{$faker->paragraph()}</p>
        <p>{$faker->paragraph()}</p>
        <h3>{$faker->sentence()}</h3>
        <p>{$faker->paragraph()}</p>
        <p>{$faker->paragraph()}</p>
        <h3>{$faker->sentence()}</h3>
        <p>{$faker->paragraph()}</p>
        <p><a href="http://example.com">{$faker->words(3, true)}</a></p>
        HTML;
    }
}

<?php

namespace Database\Factories;

use App\Enums\PostStatusEnum;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'short_description' => $this->faker->paragraph(),
            'description' => $this->faker->paragraphs(5, true),
            'status' => PostStatusEnum::ACTIVE,
            'status_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'author_id' => User::factory(),
            'category_id' => Category::inRandomOrder()->value("id"),
        ];
    }
}

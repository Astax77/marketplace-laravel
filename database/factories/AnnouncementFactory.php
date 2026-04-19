<?php

namespace Database\Factories;

use App\Models\Announcement;
use App\Models\Category;
use App\Models\City;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AnnouncementFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->sentence(4, false);

        return [
            'user_id'       => User::factory(),
            'category_id'   => Category::inRandomOrder()->value('id') ?? Category::factory(),
            'city_id'       => City::inRandomOrder()->value('id') ?? 1,
            'title'         => $title,
            'slug'          => Str::slug($title) . '-' . Str::random(6),
            'description'   => fake()->paragraphs(3, true),
            'price'         => fake()->randomFloat(2, 50, 50000),
            'is_negotiable' => fake()->boolean(40),
            'condition'     => fake()->randomElement(Announcement::CONDITIONS),
            'status'        => fake()->randomElement([
                Announcement::STATUS_ACTIVE,
                Announcement::STATUS_ACTIVE,
                Announcement::STATUS_ACTIVE,
                Announcement::STATUS_SOLD,
            ]),
            'images'        => null,
            'views_count'   => fake()->numberBetween(0, 500),
        ];
    }

    public function active(): static
    {
        return $this->state(['status' => Announcement::STATUS_ACTIVE]);
    }

    public function sold(): static
    {
        return $this->state(['status' => Announcement::STATUS_SOLD]);
    }
}

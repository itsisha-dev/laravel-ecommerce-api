<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Category>
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
        $name = $this->faker->unique()->word();

        return [
                'name' => ucfirst($name),
                'slug' => $this->faker->unique()->slug(),
                'description' => $this->faker->sentence(),
                'is_active' => true,
                'parent_id' => null,
            ];
    }
}

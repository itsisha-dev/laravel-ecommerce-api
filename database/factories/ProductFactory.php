<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->words(3, true);

        return [
            // 'vendor_id'   => \App\Models\Vendor::factory(), // or use Vendor::factory() if you have a vendor model
            'name'        => ucfirst($name),
            'slug'        => $this->faker->unique()->slug(),
            'description' => $this->faker->sentence(),
            'price'       => $this->faker->randomFloat(2, 1, 500),
            'stock'       => $this->faker->numberBetween(0, 100),
            'is_active'   => true,
        ];
    }
}

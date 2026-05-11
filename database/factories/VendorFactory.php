<?php

namespace Database\Factories;

use App\Models\Vendor;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Vendor>
 */
class VendorFactory extends Factory
{
    protected $model = Vendor::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Link to a User, create one automatically if needed
            // 'user_id'     => User::factory(),
            'store_name'  => $this->faker->company(),
            'phone'       => $this->faker->phoneNumber(),
            'address'     => $this->faker->address(),
        ];
    }
}

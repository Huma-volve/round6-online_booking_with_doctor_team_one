<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class AddressFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Address::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // Latitude and Longitude for Egypt
        $latitude = $this->faker->latitude(29.5, 31.5);
        $longitude = $this->faker->longitude(30.5, 32.5);

        return [
            'user_id' => User::factory(),
            'address' => $this->faker->streetAddress,
            'latitude' => $latitude,
            'longitude' => $longitude,
        ];
    }
}

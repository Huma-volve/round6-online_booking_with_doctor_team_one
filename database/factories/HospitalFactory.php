<?php

namespace Database\Factories;

use App\Models\Hospital;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Hospital>
 */
class HospitalFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Hospital::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->faker->unique()->company . ' Hospital';
        $startTime = $this->faker->time('H:i:s', '08:00:00');
        $endTime = $this->faker->time('H:i:s', '20:00:00');

        return [
            'name' => $name,
            'address' => $this->faker->streetAddress,
            'city' => $this->faker->city,
            'latitude' => $this->faker->latitude(29.5, 31.5),
            'longitude' => $this->faker->longitude(30.5, 32.5),
            'rate' => $this->faker->randomFloat(1, 3, 5),
        ];
    }
}

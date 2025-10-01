<?php

namespace Database\Factories;

use App\Models\History;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\History>
 */
class HistoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = History::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(), // Creates a new user if one doesn't exist
            'search_term' => $this->faker->randomElement(['Cardiologist', 'Pediatrician', 'Dermatologist', 'Nearest Hospital', 'Eye Doctor']),
            'location' => $this->faker->city,
            'search_lat' => $this->faker->latitude(29.5, 31.5),
            'search_long' => $this->faker->longitude(30.5, 32.5),
        ];
    }
}

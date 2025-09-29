<?php

namespace Database\Factories;

use App\Models\Doctor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Doctor>
 */
class DoctorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Doctor::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name('male'|'female') . ' ' . $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->unique()->phoneNumber,
            'price' => $this->faker->randomFloat(2, 50, 300),
            'rating' => $this->faker->randomFloat(2, 3.00, 5.00),
            'experience' => $this->faker->numberBetween(1, 30),
            'profile_picture' => 'doctors/' . $this->faker->image('public/storage/doctors', 200, 200, 'people', false), // Saves an image
            'bio' => $this->faker->paragraph(5),
        ];
    }
}

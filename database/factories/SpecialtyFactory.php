<?php

namespace Database\Factories;

use App\Models\Specialty;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Major>
 */
class SpecialtyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Specialty::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->faker->unique()->randomElement([
            'Cardiology', 'Pediatrics', 'Dermatology', 'Neurology', 'Orthopedics',
            'Oncology', 'Gynecology', 'Urology', 'Ophthalmology', 'Psychiatry',
            'General Surgery', 'Internal Medicine', 'Emergency Medicine', 'Radiology',
            'Anesthesiology', 'Endocrinology', 'Gastroenterology', 'Pulmonology',
            'Nephrology', 'Rheumatology'
        ]);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => $this->faker->paragraph(3),
        ];
    }
}

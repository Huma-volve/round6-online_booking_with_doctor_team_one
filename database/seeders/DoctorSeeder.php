<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\Hospital;
use App\Models\Specialty;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Doctor::factory()->count(50)->create()->each(function ($doctor) {
            // Attach random majors to each doctor
            $majors = Specialty::inRandomOrder()->limit(rand(1, 3))->get();
            $doctor->majors()->attach($majors);

            // Attach random hospitals to each doctor
            $hospitals = Hospital::inRandomOrder()->limit(rand(1, 2))->get();
            $doctor->hospitals()->attach($hospitals);
        });
    }
}

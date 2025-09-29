<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            // UserSeeder::class, 
            SpecialitySeeder::class,
            HospitalSeeder::class,
            DoctorSeeder::class,
            AddressSeeder::class,
            HistorySeeder::class,
        ]);
    }
}

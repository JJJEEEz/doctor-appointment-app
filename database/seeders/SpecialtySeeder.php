<?php

namespace Database\Seeders;

use App\Models\Specialty;
use Illuminate\Database\Seeder;

class SpecialtySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $specialties = [
            'Cardiologia',
            'Pediatria',
            'Dermatologia',
            'Ginecologia',
            'Neurologia',
            'Traumatologia',
            'Oftalmologia',
            'Medicina Interna',
        ];

        foreach ($specialties as $specialty) {
            Specialty::firstOrCreate(['name' => $specialty]);
        }
    }
}

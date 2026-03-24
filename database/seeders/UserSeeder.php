<?php

namespace Database\Seeders;

use App\Models\BloodType;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Specialty;
use App\Models\User;
use Illuminate\Database\Seeder;
    use Illuminate\Support\Facades\Hash;

    class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminUser = User::query()->updateOrCreate(
            ['email' => 'admin.pruebas@demo.test'],
            [
                'name' => 'Admin Pruebas',
                'password' => Hash::make('password'),
                'id_number' => 'ADM-0001',
                'phone' => '5551000001',
                'address' => 'Direccion Admin',
            ]
        );
        $adminUser->syncRoles(['Administrador']);

        $patientUser = User::query()->updateOrCreate(
            ['email' => 'paciente.pruebas@demo.test'],
            [
                'name' => 'Paciente Pruebas',
                'password' => Hash::make('password'),
                'id_number' => 'PAC-0001',
                'phone' => '5551000002',
                'address' => 'Direccion Paciente',
            ]
        );
        $patientUser->syncRoles(['Paciente']);

        $doctorUser = User::query()->updateOrCreate(
            ['email' => 'doctor.pruebas@demo.test'],
            [
                'name' => 'Doctor Pruebas',
                'password' => Hash::make('password'),
                'id_number' => 'DOC-0001',
                'phone' => '5551000003',
                'address' => 'Direccion Doctor',
            ]
        );
        $doctorUser->syncRoles(['Doctor']);

        $bloodType = BloodType::query()->first();
        $specialty = Specialty::query()->first();

        if (! $bloodType || ! $specialty) {
            $this->command?->warn('UserSeeder: faltan blood_types o specialties.');

            return;
        }

        Patient::query()->updateOrCreate(
            ['user_id' => $patientUser->id],
            [
                'blood_type_id' => $bloodType->id,
                'name' => 'Paciente Pruebas',
                'email' => 'paciente.pruebas@demo.test',
                'phone' => '5551000002',
                'address' => 'Direccion Paciente',
            ]
        );

        Doctor::query()->updateOrCreate(
            ['user_id' => $doctorUser->id],
            [
                'speciality_id' => $specialty->id,
                'medical_license_number' => 'MED-PRUEBAS-0001',
                'biography' => 'Doctor de pruebas para validar envios de correo y reportes.',
            ]
        );
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //LLamar a RoleSeeder
        $this->call(
            RoleSeeder::class
        );

        //Definir roles
        $roles = [
            'Paciente',
            'Doctor',
            'Recepcionista',
            'Administrador',
        ];
        //Crear en la BD
        foreach ($roles as $role) {
            Role::create([
                'name' => $role
            ]);
        }
    }
}
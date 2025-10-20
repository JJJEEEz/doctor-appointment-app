<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
         //Crear un usuario de prueba cada que se ejecuten migraciones
        //php artisan migrate:refresh --seed
        User::factory()->create([
            'name' => 'Juan Perez',
            'email' => 'admin@admin.admin',
            'password'=> bcrypt('12345678'),
        ]);
    }
}

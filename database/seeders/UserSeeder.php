<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        $user = User::create([
            'name' => 'Rodrigo',
            'email' => 'rodrigo@software.com.mx',
            'password' => bcrypt('password'),
            'id_number' => '123456789',
            'phone' => '1234567890',
            'address' => 'Calle Falsa 123',
        ]);
        $user->assignRole('doctor');
    }
}

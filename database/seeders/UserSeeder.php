<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        $user = User::firstOrCreate(
            ['email' => 'rodrigo@software.com.mx'],
            [
                'name' => 'Rodrigo',
                'password' => bcrypt('password'),
                'id_number' => '123456789',
                'phone' => '1234567890',
                'address' => 'Calle Falsa 123',
            ]
        );
        $user->syncRoles(['Doctor']);
    }
}

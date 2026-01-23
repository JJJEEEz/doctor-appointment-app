<?php
use App\Models\User;
use Spatie\Permission\Models\Role;

test('Se puede actualizar un usuario', function () {
    // 1) Creamos 2 usuarios de prueba

        // El primer usuario será el que actualice
    $user = User::factory()->create();

        // El segundo usuario será el actualizado
    $userToUpdate = User::factory()->create();

        // Creamos un rol para asignar
    $role = Role::firstOrCreate(['name' => 'Doctor']);

    // 2) Autenticamos al primer usuario
    $this->actingAs($user);

    // 3) Simulamos una petición HTTP para actualizar al segundo usuario
    $response = $this->put(route('admin.users.update', $userToUpdate->id), [
        'name' => 'Nombre Actualizado',
        'email' => 'nombreactualizado@example.com',
        'password' => 'Newpassword123',
        'password_confirmation' => 'Newpassword123',
        'id_number' => '987654321',
        'phone' => '1234567890',
        'address' => 'Calle Actualizada 123',
        'role' => 'Doctor', 
    ]);

    // 4) Verificamos la respuesta del servidor
    $response->assertSessionHasNoErrors();
    $response->assertRedirect(route('admin.users.index'));

});

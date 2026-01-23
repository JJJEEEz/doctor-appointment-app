<?php
use App\Models\User;

test('Un usuario tipo admin puede crear un rol', function () {
    // 1) Creamos un usuario de prueba
    $user = User::factory()->create();

    // 2) Autenticamos al usuario
    $this->actingAs($user);

    // 3) Simulamos una petición HTTP POST para crear un nuevo rol
    $response = $this->post(route('admin.roles.store'), [
        'name' => 'editor',
    ]);

    // 4) Verificamos que la respuesta sea una redirección a la lista de roles
    $response->assertRedirect(route('admin.roles.index'));

    // 5) Verificamos que el rol se haya creado en la base de datos
    $this->assertDatabaseHas('roles', [
        'name' => 'editor',
    ]);
});

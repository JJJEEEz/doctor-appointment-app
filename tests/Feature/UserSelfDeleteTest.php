<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

// Usamos la cualidad para refrescar la base de datos en cada prueba
uses(RefreshDatabase::class);

test('Un usuario no puede eliminarse a sí mismo', function () {

    // 1) Crear un usuario de prueba
    $user = User::factory()->create();

    // 2) Autenticar al usuario
    $this->actingAs($user);

   // 3) Simulamos una petición HTTP DELETE
   $response = $this->delete(route('admin.users.destroy', $user->id)); 

    // 4) Esperamos que el servidor bloquee la acción
    $response->assertStatus(403); // 403 Forbidden

    // 5) Verificamos que el usuario aún existe en la base de datos
    $this->assertDatabaseHas('users', ['id' => $user->id]);
});

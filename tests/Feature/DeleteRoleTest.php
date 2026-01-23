<?php
use App\Models\User;
use Spatie\Permission\Models\Role;

test('Un rol puede ser eliminado', function () {
    // 1) Creamos un usuario
    $user = User::factory()->create();

    // 2) Creamos un rol
    $rol = Role::create(['name' => 'Rol a eliminar']);

    // 3) Autenticamos al usuario
    $this->actingAs($user);

    // 4) Simulamos una peticion HTTP para eliminar el rol
    $response = $this->delete(route('admin.roles.destroy', $rol->id));

    // 5) verificamos la respuesta del servidor
    $response->assertSessionHasNoErrors();
    $response->assertRedirect(route('admin.roles.index'));

});

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index()
    {
        $users = User::with('roles')->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        $roles = Role::all();
        $breadcrumbs = [
            ['name' => 'Usuarios', 'href' => route('admin.users.index')],
            ['name' => 'Crear']
        ];

        return view('admin.users.create', compact('roles', 'breadcrumbs'));
    }

    /**
     * Store a newly created user in storage
     */
    public function store(Request $request)
    {
        // Validaciones mejoradas con formato internacional para ID
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'id_number' => ['required', 'string', 'max:50', 'unique:users,id_number'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string', 'exists:roles,name']
        ], [
            'name.required' => 'El nombre es obligatorio',
            'email.required' => 'El email es obligatorio',
            'email.unique' => 'Este email ya está registrado',
            'id_number.required' => 'El número de identificación es obligatorio',
            'id_number.unique' => 'Este número de identificación ya está registrado',
            'password.required' => 'La contraseña es obligatoria',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres',
            'password.confirmed' => 'Las contraseñas no coinciden',
            'role.required' => 'Debe seleccionar un rol',
            'role.exists' => 'El rol seleccionado no es válido'
        ]);

        // Crear usuario con contraseña hasheada
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'id_number' => $data['id_number'],
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
            'password' => Hash::make($data['password']),
        ]);

        // Asignar rol usando Spatie Permission
        $user->assignRole($data['role']);

        session()->flash('swal', [
            'title' => '¡Éxito!', 
            'text' => 'Usuario creado correctamente.', 
            'icon' => 'success'
        ]);

        return redirect()->route('admin.users.index');
    }

    /**
     * Display the specified user
     */
    public function show(User $user)
    {
        $breadcrumbs = [
            ['name' => 'Usuarios', 'href' => route('admin.users.index')],
            ['name' => 'Ver']
        ];

        return view('admin.users.show', compact('user', 'breadcrumbs'));
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        $breadcrumbs = [
            ['name' => 'Usuarios', 'href' => route('admin.users.index')],
            ['name' => 'Editar']
        ];

        return view('admin.users.edit', compact('user', 'roles', 'breadcrumbs'));
    }

    /**
     * Update the specified user in storage
     */
    public function update(Request $request, User $user)
    {
        // Validaciones con unique ignore para el usuario actual
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'id_number' => ['required', 'string', 'max:50', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string', 'exists:roles,name']
        ], [
            'name.required' => 'El nombre es obligatorio',
            'email.required' => 'El email es obligatorio',
            'email.unique' => 'Este email ya está registrado',
            'id_number.required' => 'El número de identificación es obligatorio',
            'id_number.unique' => 'Este número de identificación ya está registrado',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres',
            'password.confirmed' => 'Las contraseñas no coinciden',
            'role.required' => 'Debe seleccionar un rol',
            'role.exists' => 'El rol seleccionado no es válido'
        ]);

        // Actualizar campos del usuario
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->id_number = $data['id_number'];
        $user->phone = $data['phone'] ?? null;
        $user->address = $data['address'] ?? null;

        // Actualizar contraseña solo si se proporcionó
        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        // Sincronizar rol (reemplaza todos los roles previos)
        $user->syncRoles([$data['role']]);

        session()->flash('swal', [
            'title' => '¡Éxito!', 
            'text' => 'Usuario actualizado correctamente.', 
            'icon' => 'success'
        ]);

        return redirect()->route('admin.users.index');
    }

    /**
     * Remove the specified user from storage
     */
    public function destroy(User $user)
    {
        // Prevenir que el admin se elimine a sí mismo
        if ($user->id === Auth::id()) {
            session()->flash('swal', [
                'title' => 'Error', 
                'text' => 'No puedes eliminar tu propia cuenta.', 
                'icon' => 'error'
            ]);
            
            return redirect()->route('admin.users.index');
        }

        // Desvincular roles antes de eliminar
        $user->roles()->detach();
        
        // Eliminar usuario (soft delete si está habilitado en el modelo)
        $user->delete();

        session()->flash('swal', [
            'title' => '¡Eliminado!', 
            'text' => 'Usuario eliminado correctamente.', 
            'icon' => 'success'
        ]);

        return redirect()->route('admin.users.index');
    }
}

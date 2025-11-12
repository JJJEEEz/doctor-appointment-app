<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        $breadcrumbs = [
            ['name' => 'Usuarios', 'href' => route('admin.users.index')],
            ['name' => 'Crear']
        ];

        return view('admin.users.create', compact('roles', 'breadcrumbs'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'roles' => 'array'
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        if (!empty($data['roles'])) {
            $user->syncRoles($data['roles']);
        }

        session()->flash('swal', ['title' => 'Creado', 'text' => 'Usuario creado correctamente.', 'icon' => 'success']);

        return redirect()->route('admin.users.index');
    }

    public function show(User $user)
    {
        $breadcrumbs = [
            ['name' => 'Usuarios', 'href' => route('admin.users.index')],
            ['name' => 'Ver']
        ];

        return view('admin.users.show', compact('user', 'breadcrumbs'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $breadcrumbs = [
            ['name' => 'Usuarios', 'href' => route('admin.users.index')],
            ['name' => 'Editar']
        ];

        return view('admin.users.edit', compact('user', 'roles', 'breadcrumbs'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'roles' => 'array'
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        $user->syncRoles($data['roles'] ?? []);

        session()->flash('swal', ['title' => 'Actualizado', 'text' => 'Usuario actualizado correctamente.', 'icon' => 'success']);

        return redirect()->route('admin.users.index');
    }

    public function destroy(User $user)
    {
        $user->delete();

        session()->flash('swal', ['title' => 'Eliminado', 'text' => 'Usuario eliminado correctamente.', 'icon' => 'success']);

        return redirect()->route('admin.users.index');
    }
}

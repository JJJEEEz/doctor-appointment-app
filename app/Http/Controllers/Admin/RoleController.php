<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.roles.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Mostrar el formulario de creación
        return view('admin.roles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|unique:roles,name',
        ]);

        $role = Role::create([
            'name' => $data['name'],
        ]);

        // Flash a SweetAlert payload to the session for the layout to show
        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Rol creado',
            'text' => 'El rol se ha creado correctamente.',
        ]);

        return redirect()->route('admin.roles.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $role = \Spatie\Permission\Models\Role::findOrFail($id);
        return view('admin.roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $role = \Spatie\Permission\Models\Role::findOrFail($id);
        return view('admin.roles.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $role = Role::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|unique:roles,name,' . $role->id,
        ]);

        $role->update([
            'name' => $data['name'],
        ]);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Rol actualizado',
            'text' => 'Los cambios se guardaron correctamente.',
        ]);

        return redirect()->route('admin.roles.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = \Spatie\Permission\Models\Role::findOrFail($id);
        $role->delete();

        return redirect()->route('admin.roles.index')->with('message', 'Rol eliminado correctamente.');
    }
}

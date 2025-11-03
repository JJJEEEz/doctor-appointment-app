<?php

use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // Redirige a la ruta de admin después de iniciar sesión
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Agrupamos las rutas de admin aquí para protegerlas
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        // Rutas para roles
        Route::get('roles', function () {
            return view('admin.roles.index');
        })->name('roles.index');

        Route::get('roles/create', function () {
            return view('admin.roles.create');
        })->name('roles.create');

        Route::get('roles/{role}', function ($role) {
            $role = \Spatie\Permission\Models\Role::findOrFail($role);
            return view('admin.roles.show', compact('role'));
        })->name('roles.show');

        Route::get('roles/{role}/edit', function ($role) {
            $role = \Spatie\Permission\Models\Role::findOrFail($role);
            return view('admin.roles.edit', compact('role'));
        })->name('roles.edit');
    });
});
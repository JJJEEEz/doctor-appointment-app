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
        // Load admin routes from routes/admin.php (resource controllers, etc.)
        require base_path('routes/admin.php');
    });
});
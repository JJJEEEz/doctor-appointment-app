<?php

use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DoctorController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PacientController;

Route::get('/', function () {
    return view(('admin.dashboard'));
})->name('dashboard');

// Gestion de roles
Route::resource(
    'roles', 
    RoleController::class
);

// Gestion de usuarios
Route::resource('users', UserController::class);

// Gestion de pacientes
Route::resource('patients', PacientController::class);

// Gestion de doctores
Route::resource('doctors', DoctorController::class)->except(['show']);
Route::get('doctors/{doctor}/show', [DoctorController::class, 'show'])->name('doctors.show');
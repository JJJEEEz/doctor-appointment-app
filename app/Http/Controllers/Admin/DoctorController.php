<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Specialty;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.doctors.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $specialties = Specialty::query()->orderBy('name')->get();

        return view('admin.doctors.create', compact('specialties'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'min:3',
                'max:255',
                'regex:/^[a-záéíóúñ\s\-\']+$/i'
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email'
            ],
            'id_number' => [
                'required',
                'string',
                'min:5',
                'max:50',
                'regex:/^[a-z0-9\-]+$/i',
                'unique:users,id_number'
            ],
            'phone' => [
                'nullable',
                'digits:10',
                'regex:/^[0-9]{10}$/'
            ],
            'address' => [
                'nullable',
                'string',
                'max:500'
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'max:255',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/'
            ],
            'speciality_id' => [
                'nullable',
                'exists:specialties,id',
            ],
            'medical_license_number' => ['nullable', 'string', 'max:255'],
            'biography' => ['nullable', 'string'],
        ], [
            'name.required' => 'El nombre es obligatorio',
            'name.min' => 'El nombre debe tener al menos 3 caracteres',
            'name.regex' => 'El nombre solo puede contener letras, espacios, guiones y apóstrofos',
            'email.required' => 'El email es obligatorio',
            'email.email' => 'El email debe ser válido',
            'email.unique' => 'Este email ya está registrado',
            'id_number.required' => 'El número de identificación es obligatorio',
            'id_number.min' => 'El número de identificación debe tener al menos 5 caracteres',
            'id_number.regex' => 'El número de identificación solo puede contener letras, números y guiones',
            'id_number.unique' => 'Este número de identificación ya está registrado',
            'phone.digits' => 'El teléfono debe tener exactamente 10 dígitos',
            'phone.regex' => 'El teléfono solo puede contener números',
            'password.required' => 'La contraseña es obligatoria',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres',
            'password.confirmed' => 'Las contraseñas no coinciden',
            'password.regex' => 'La contraseña debe contener al menos una mayúscula, una minúscula y un número',
            'speciality_id.exists' => 'La especialidad seleccionada no es valida.',
            'medical_license_number.max' => 'La cedula profesional no puede exceder 255 caracteres.',
        ]);

        // Crear usuario
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'id_number' => $data['id_number'],
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
            'password' => Hash::make($data['password']),
        ]);

        // Asignar rol Doctor
        $user->assignRole('Doctor');

        // Crear registro de doctor
        $doctor = Doctor::create([
            'user_id' => $user->id,
            'speciality_id' => $data['speciality_id'] ?? null,
            'medical_license_number' => $data['medical_license_number'] ?? null,
            'biography' => $data['biography'] ?? null,
        ]);

        session()->flash('swal', [
            'title' => '¡Exito!',
            'text' => 'Doctor creado correctamente.',
            'icon' => 'success',
        ]);

        return redirect()->route('admin.doctors.edit', $doctor);
    }

    /**
     * Display the specified resource.
     */
    public function show(Doctor $doctor)
    {
        return view('admin.doctors.show', compact('doctor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Doctor $doctor)
    {
        $specialties = Specialty::query()->orderBy('name')->get();

        return view('admin.doctors.edit', compact('doctor', 'specialties'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Doctor $doctor)
    {
        $data = $request->validate([
            'speciality_id' => [
                'nullable',
                'exists:specialties,id',
            ],
            'medical_license_number' => ['nullable', 'string', 'max:255'],
            'biography' => ['nullable', 'string'],
        ], [
            'speciality_id.exists' => 'La especialidad seleccionada no es valida.',
            'medical_license_number.max' => 'La cedula profesional no puede exceder 255 caracteres.',
        ]);

        $doctor->update($data);

        session()->flash('swal', [
            'title' => '¡Exito!',
            'text' => 'Doctor actualizado correctamente.',
            'icon' => 'success',
        ]);

        return redirect()->route('admin.doctors.edit', $doctor);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Doctor $doctor)
    {
        // Eliminar el doctor (el usuario asociado permanece)
        $doctor->delete();

        session()->flash('swal', [
            'title' => '¡Eliminado!',
            'text' => 'Doctor eliminado correctamente.',
            'icon' => 'success',
        ]);

        return redirect()->route('admin.doctors.index');
    }
}

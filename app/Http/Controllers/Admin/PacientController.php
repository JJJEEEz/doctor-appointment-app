<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BloodType;
use App\Models\Patient;
use Illuminate\Http\Request;

class PacientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.patients.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return view('admin.patients.create');
    }

    /**
     * Display the specified resource.
     */
    public function show(Patient $patient)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Patient $patient)
    {
        $bloodTypes = BloodType::query()->orderBy('name')->get();

        return view('admin.patients.edit', compact('patient', 'bloodTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Patient $patient)
    {
        $data = $request->validate([
            'blood_type_id' => [
                'nullable',
                'exists:blood_types,id',
            ],
            'known_allergies' => ['nullable', 'string', 'max:255'],
            'chronic_diseases' => ['nullable', 'string', 'max:255'],
            'surgical_history' => ['nullable', 'string', 'max:255'],
            'family_history' => ['nullable', 'string', 'max:255'],
            'observations' => ['nullable', 'string', 'max:255'],
            'emergency_contact_name' => ['nullable', 'string', 'max:255'],
            'emergency_contact_phone' => ['nullable', 'digits:10'],
            'emergency_contact_relationship' => ['nullable', 'string', 'max:255'],
        ], [
            'blood_type_id.exists' => 'El tipo de sangre seleccionado no es valido.',
            'known_allergies.max' => 'Las alergias conocidas no pueden exceder 255 caracteres.',
            'chronic_diseases.max' => 'Las enfermedades cronicas no pueden exceder 255 caracteres.',
            'surgical_history.max' => 'Los antecedentes quirurgicos no pueden exceder 255 caracteres.',
            'family_history.max' => 'Los antecedentes familiares no pueden exceder 255 caracteres.',
            'observations.max' => 'Las observaciones no pueden exceder 255 caracteres.',
            'emergency_contact_name.max' => 'El nombre del contacto no puede exceder 255 caracteres.',
            'emergency_contact_phone.digits' => 'El telefono del contacto debe tener exactamente 10 digitos.',
            'emergency_contact_relationship.max' => 'La relacion con el contacto no puede exceder 255 caracteres.',
        ]);

        $patient->update($data);

        session()->flash('swal', [
            'title' => '¡Exito!',
            'text' => 'Paciente actualizado correctamente.',
            'icon' => 'success',
        ]);

        return redirect()->route('admin.patients.edit', $patient);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patient $patient)
    {
        //
    }
}

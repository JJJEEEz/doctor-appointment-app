<?php

namespace App\Http\Controllers\Admin;

use App\Models\Appointment;
use App\Http\Controllers\Controller;

class ConsultationController extends Controller
{
    public function show(Appointment $appointment)
    {
        $appointment->load(['doctor.user', 'patient.bloodType']);
        $previousConsultations = Appointment::query()
            ->with('doctor.user')
            ->where('patient_id', $appointment->patient_id)
            ->where('id', '!=', $appointment->id)
            ->where(function ($query) {
                $query->whereNotNull('diagnosis')->orWhereNotNull('treatment');
            })
            ->orderByDesc('date')
            ->orderByDesc('start_time')
            ->get();

        return view('admin.appointments.consultation', [
            'appointment' => $appointment,
            'previousConsultations' => $previousConsultations,
        ]);
    }

    public function store(Appointment $appointment)
    {
        $validated = request()->validate([
            'diagnosis' => 'required|string',
            'treatment' => 'required|string',
            'consultation_notes' => 'nullable|string',
            'prescriptions' => 'nullable|array',
        ]);

        $appointment->update([
            'diagnosis' => $validated['diagnosis'],
            'treatment' => $validated['treatment'],
            'consultation_notes' => $validated['consultation_notes'] ?? null,
            'prescriptions' => $validated['prescriptions'] ?? [],
            'status' => 2, // STATUS_COMPLETED
        ]);

        return redirect()->route('admin.appointments.index')->with('swal', [
            'title' => 'Exito',
            'text' => 'Consulta guardada correctamente.',
            'icon' => 'success',
        ]);
    }
}

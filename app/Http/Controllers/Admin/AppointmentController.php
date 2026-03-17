<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendAppointmentConfirmationJob;
use App\Models\Appointment;
use App\Models\Patient;
use App\Services\AppointmentConflictService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AppointmentController extends Controller
{
    public function __construct(private readonly AppointmentConflictService $conflictService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.appointments.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.appointments.create', [
            'patients' => Patient::query()->orderBy('name')->get(),
            'doctors' => \App\Models\Doctor::query()->with('user')->orderBy('id')->get(),
            'statusOptions' => $this->statusOptions(),
            'today' => now()->format('Y-m-d'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateAppointmentData($request);
        $this->validateConflicts($data);

        $appointment = Appointment::create($data);

        try {
            SendAppointmentConfirmationJob::dispatchSync($appointment->id);
        } catch (\Throwable $exception) {
            Log::warning('No se pudo enviar la confirmacion de WhatsApp.', [
                'appointment_id' => $appointment->id,
                'error' => $exception->getMessage(),
            ]);
        }

        session()->flash('swal', [
            'title' => '¡Exito!',
            'text' => 'Cita creada correctamente.',
            'icon' => 'success',
        ]);

        return redirect()->route('admin.appointments.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Appointment $appointment)
    {
        $appointment->load(['doctor.user', 'doctor.speciality', 'patient']);

        return view('admin.appointments.show', compact('appointment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Appointment $appointment)
    {
        $appointment->load(['doctor.user', 'doctor.speciality']);

        return view('admin.appointments.edit', [
            'appointment' => $appointment,
            'patients' => Patient::query()->orderBy('name')->get(),
            'doctors' => \App\Models\Doctor::query()->with('user')->orderBy('id')->get(),
            'statusOptions' => $this->statusOptions(),
            'today' => now()->format('Y-m-d'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Appointment $appointment): RedirectResponse
    {
        $data = $this->validateAppointmentData($request);
        $this->validateConflicts($data, $appointment->id);

        $appointment->update($data);

        session()->flash('swal', [
            'title' => '¡Exito!',
            'text' => 'Cita actualizada correctamente.',
            'icon' => 'success',
        ]);

        return redirect()->route('admin.appointments.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment): RedirectResponse
    {
        $appointment->delete();

        session()->flash('swal', [
            'title' => '¡Eliminado!',
            'text' => 'Cita eliminada correctamente.',
            'icon' => 'success',
        ]);

        return redirect()->route('admin.appointments.index');
    }

    private function validateAppointmentData(Request $request): array
    {
        $data = $request->validate([
            'patient_id' => ['required', 'exists:patients,id'],
            'doctor_id' => ['required', 'exists:doctors,id'],
            'date' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'reason' => ['nullable', 'string'],
            'status' => ['required', 'integer', 'in:0,1,2'],
        ], [
            'patient_id.required' => 'Debe seleccionar un paciente.',
            'doctor_id.required' => 'Debe seleccionar un doctor.',
            'date.required' => 'Debe indicar la fecha de la cita.',
            'date.after_or_equal' => 'La fecha de la cita no puede ser en el pasado.',
            'start_time.required' => 'Debe indicar la hora de inicio.',
            'end_time.required' => 'Debe indicar la hora de fin.',
            'end_time.after' => 'La hora de termino debe ser mayor a la hora de inicio.',
            'status.required' => 'Debe seleccionar el estado de la cita.',
        ]);

        $data['duration'] = $this->computeDuration($data['start_time'], $data['end_time']);

        return $data;
    }

    private function validateConflicts(array $data, ?int $ignoreAppointmentId = null): void
    {
        if ($this->conflictService->doctorHasConflict((int) $data['doctor_id'], $data['date'], $data['start_time'], $data['end_time'], $ignoreAppointmentId)) {
            throw ValidationException::withMessages([
                'doctor_id' => 'El doctor ya tiene una cita programada en ese horario.',
            ]);
        }

        if ($this->conflictService->patientHasConflict((int) $data['patient_id'], $data['date'], $data['start_time'], $data['end_time'], $ignoreAppointmentId)) {
            throw ValidationException::withMessages([
                'patient_id' => 'El paciente ya tiene una cita en ese horario.',
            ]);
        }
    }

    private function computeDuration(string $startTime, string $endTime): int
    {
        $start = Carbon::createFromFormat('H:i', $startTime);
        $end = Carbon::createFromFormat('H:i', $endTime);

        return max(15, $start->diffInMinutes($end));
    }

    private function statusOptions(): array
    {
        return [
            Appointment::STATUS_SCHEDULED => 'Programada',
            Appointment::STATUS_COMPLETED => 'Completada',
            Appointment::STATUS_CANCELLED => 'Cancelada',
        ];
    }
}

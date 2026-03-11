<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Specialty;
use App\Services\AppointmentConflictService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
    public function create(Request $request)
    {
        $filters = [
            'appointment_date' => $request->query('appointment_date', old('appointment_date')),
            'start_time' => $request->query('start_time', old('start_time')),
            'speciality_id' => $request->query('speciality_id', old('speciality_id')),
        ];

        $filters['end_time'] = !empty($filters['start_time'])
            ? $this->computeEndTime($filters['start_time'])
            : null;

        $availableDoctors = collect();
        $searchPerformed = $this->hasSearchFilters($filters);
        $nearbyAvailability = ['before' => null, 'after' => null];

        if ($searchPerformed) {
            $request->validate([
                'appointment_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
                'start_time' => ['required', 'date_format:H:i'],
                'speciality_id' => ['nullable', 'exists:specialties,id'],
            ]);

            $this->validateAppointmentSchedule($filters['appointment_date'], $filters['start_time']);

            $availableDoctors = $this->conflictService
                ->availableDoctors(
                    $filters['appointment_date'],
                    $filters['start_time'],
                    $filters['end_time'],
                    $filters['speciality_id'] ? (int) $filters['speciality_id'] : null
                )
                ->get();

            if ($availableDoctors->isEmpty()) {
                $nearbyAvailability = $this->nearestAvailableSlots(
                    $filters['appointment_date'],
                    $filters['start_time'],
                    $filters['speciality_id'] ? (int) $filters['speciality_id'] : null
                );
            }
        }

        return view('admin.appointments.create', [
            'patients' => Patient::query()->orderBy('name')->get(),
            'specialties' => Specialty::query()->whereHas('doctors')->orderBy('name')->get(),
            'availableDoctors' => $availableDoctors,
            'filters' => $filters,
            'statuses' => $this->statuses(),
            'today' => now()->format('Y-m-d'),
            'minTimeToday' => now()->addHours(2)->format('H:i'),
            'searchPerformed' => $searchPerformed,
            'nearbyAvailability' => $nearbyAvailability,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateAppointmentData($request);

        $this->ensureDoctorSpecialityMatches($data);
        $this->validateAvailabilityAndConflicts($data);

        Appointment::create($data);

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
    public function edit(Request $request, Appointment $appointment)
    {
        $appointment->load(['doctor.user', 'doctor.speciality']);

        $filters = [
            'appointment_date' => $request->query('appointment_date', Carbon::parse($appointment->appointment_date)->format('Y-m-d')),
            'start_time' => $request->query('start_time', substr((string) $appointment->start_time, 0, 5)),
            'speciality_id' => $request->query('speciality_id', $appointment->doctor?->speciality_id),
        ];

        $filters['end_time'] = $this->computeEndTime($filters['start_time']);

        $availableDoctors = collect();

        if ($this->hasSearchFilters($filters)) {
            $request->validate([
                'appointment_date' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:today'],
                'start_time' => ['nullable', 'date_format:H:i'],
                'speciality_id' => ['nullable', 'exists:specialties,id'],
            ]);

            $availableDoctors = $this->conflictService
                ->availableDoctors(
                    $filters['appointment_date'],
                    $filters['start_time'],
                    $filters['end_time'],
                    $filters['speciality_id'] ? (int) $filters['speciality_id'] : null
                )
                ->get();

            if (!$availableDoctors->contains('id', $appointment->doctor_id)) {
                $availableDoctors->push($appointment->doctor);
            }

            $availableDoctors = $availableDoctors->unique('id')->values();
        }

        return view('admin.appointments.edit', [
            'appointment' => $appointment,
            'patients' => Patient::query()->orderBy('name')->get(),
            'specialties' => Specialty::query()->whereHas('doctors')->orderBy('name')->get(),
            'availableDoctors' => $availableDoctors,
            'filters' => $filters,
            'statuses' => $this->statuses(),
            'today' => now()->format('Y-m-d'),
            'minTimeToday' => now()->addHours(2)->format('H:i'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Appointment $appointment): RedirectResponse
    {
        $data = $this->validateAppointmentData($request);

        $this->ensureDoctorSpecialityMatches($data);
        $this->validateAvailabilityAndConflicts($data, $appointment->id);

        $appointment->update($data);

        session()->flash('swal', [
            'title' => '¡Exito!',
            'text' => 'Cita actualizada correctamente.',
            'icon' => 'success',
        ]);

        return redirect()->route('admin.appointments.edit', $appointment);
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
            'appointment_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
            'start_time' => ['required', 'date_format:H:i'],
            'status' => ['required', 'in:' . implode(',', $this->statuses())],
            'notes' => ['nullable', 'string', 'max:2000'],
            'speciality_id' => ['nullable', 'exists:specialties,id'],
        ], [
            'patient_id.required' => 'Debe seleccionar un paciente.',
            'doctor_id.required' => 'Debe seleccionar un doctor disponible.',
            'appointment_date.required' => 'Debe indicar la fecha de la cita.',
            'appointment_date.after_or_equal' => 'La fecha de la cita no puede ser en el pasado.',
            'start_time.required' => 'Debe indicar la hora de la cita.',
            'status.required' => 'Debe seleccionar el estado de la cita.',
        ]);

        $this->validateAppointmentSchedule($data['appointment_date'], $data['start_time']);

        $data['end_time'] = $this->computeEndTime($data['start_time']);

        return $data;
    }

    private function validateAvailabilityAndConflicts(array $data, ?int $ignoreAppointmentId = null): void
    {
        $doctor = Doctor::query()->findOrFail((int) $data['doctor_id']);
        $date = Carbon::createFromFormat('Y-m-d', $data['appointment_date']);

        if (empty($this->conflictService->requiredSlots($data['start_time'], $data['end_time']))) {
            throw ValidationException::withMessages([
                'start_time' => 'La hora de la cita no corresponde a un bloque valido de horario.',
            ]);
        }

        if (!$this->conflictService->hasAvailability($doctor, $date, $data['start_time'], $data['end_time'])) {
            throw ValidationException::withMessages([
                'doctor_id' => 'El doctor seleccionado no tiene disponibilidad completa en el rango horario indicado.',
            ]);
        }

        if ($this->conflictService->doctorHasConflict((int) $data['doctor_id'], $data['appointment_date'], $data['start_time'], $data['end_time'], $ignoreAppointmentId)) {
            throw ValidationException::withMessages([
                'doctor_id' => 'El doctor ya tiene una cita programada en ese horario.',
            ]);
        }

        if ($this->conflictService->patientHasConflict((int) $data['patient_id'], $data['appointment_date'], $data['start_time'], $data['end_time'], $ignoreAppointmentId)) {
            throw ValidationException::withMessages([
                'patient_id' => 'El paciente ya tiene una cita en ese horario.',
            ]);
        }
    }

    private function ensureDoctorSpecialityMatches(array $data): void
    {
        if (empty($data['speciality_id'])) {
            return;
        }

        $doctorMatchesSpeciality = Doctor::query()
            ->whereKey((int) $data['doctor_id'])
            ->where('speciality_id', (int) $data['speciality_id'])
            ->exists();

        if (!$doctorMatchesSpeciality) {
            throw ValidationException::withMessages([
                'doctor_id' => 'El doctor seleccionado no pertenece a la especialidad indicada.',
            ]);
        }
    }

    private function statuses(): array
    {
        return ['Programado', 'Completado', 'Cancelado'];
    }

    private function hasSearchFilters(array $filters): bool
    {
        return !empty($filters['appointment_date'])
            && !empty($filters['start_time']);
    }

    private function computeEndTime(string $startTime): string
    {
        return Carbon::createFromFormat('H:i', substr($startTime, 0, 5))->addMinutes(30)->format('H:i');
    }

    private function validateAppointmentSchedule(string $date, string $startTime): void
    {
        $start = Carbon::createFromFormat('H:i', substr($startTime, 0, 5));
        $workStart = Carbon::createFromFormat('H:i', '08:00');
        $workEnd = Carbon::createFromFormat('H:i', '19:30');

        if ($start->lt($workStart) || $start->gt($workEnd)) {
            throw ValidationException::withMessages([
                'start_time' => 'Las citas solo pueden agendarse entre las 08:00 y las 19:30 (la ultima cita finaliza a las 20:00).',
            ]);
        }

        if ($date === Carbon::today()->format('Y-m-d')) {
            $minTime = Carbon::now()->addHours(2);
            $appointmentTime = Carbon::today()->setTimeFromTimeString(substr($startTime, 0, 5) . ':00');

            if ($appointmentTime->lt($minTime)) {
                throw ValidationException::withMessages([
                    'start_time' => 'Para citas de hoy, la hora debe ser al menos 2 horas mayor a la actual (minimo: ' . $minTime->format('H:i') . ').',
                ]);
            }
        }
    }

    /**
     * @return array{before: array{start: string, end: string, doctors_count: int}|null, after: array{start: string, end: string, doctors_count: int}|null}
     */
    private function nearestAvailableSlots(string $date, string $targetStartTime, ?int $specialityId = null): array
    {
        $before = null;
        $after = null;
        $target = Carbon::createFromFormat('H:i', substr($targetStartTime, 0, 5));

        foreach ($this->timeSlots() as $slotStart) {
            if ($slotStart === $targetStartTime) {
                continue;
            }

            $slotEnd = $this->computeEndTime($slotStart);
            $availableCount = $this->conflictService
                ->availableDoctors($date, $slotStart, $slotEnd, $specialityId)
                ->count();

            if ($availableCount === 0) {
                continue;
            }

            $slot = Carbon::createFromFormat('H:i', $slotStart);

            if ($slot->lt($target)) {
                $before = [
                    'start' => $slotStart,
                    'end' => $slotEnd,
                    'doctors_count' => $availableCount,
                ];
                continue;
            }

            if ($slot->gt($target) && $after === null) {
                $after = [
                    'start' => $slotStart,
                    'end' => $slotEnd,
                    'doctors_count' => $availableCount,
                ];
                break;
            }
        }

        return ['before' => $before, 'after' => $after];
    }

    /**
     * @return list<string>
     */
    private function timeSlots(): array
    {
        $slots = [];
        $cursor = Carbon::createFromFormat('H:i', '08:00');
        $limit = Carbon::createFromFormat('H:i', '19:30');

        while ($cursor->lte($limit)) {
            $slots[] = $cursor->format('H:i');
            $cursor->addMinutes(30);
        }

        return $slots;
    }
}

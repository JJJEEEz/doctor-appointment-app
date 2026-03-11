<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class DoctorAvailabilityController extends Controller
{
    public function edit(Doctor $doctor)
    {
        $doctor->load('user');

        $existing = $doctor->availabilities()
            ->get(['day_of_week', 'start_time'])
            ->map(fn ($slot) => $slot->day_of_week . '|' . substr((string) $slot->start_time, 0, 5))
            ->all();

        return view('admin.doctors.availability', [
            'doctor' => $doctor,
            'weekdays' => $this->weekdays(),
            'hourGroups' => $this->hourGroups(),
            'existingSlots' => $existing,
        ]);
    }

    public function update(Request $request, Doctor $doctor): RedirectResponse
    {
        $data = $request->validate([
            'slots' => ['nullable', 'array'],
            'slots.*' => ['string', 'regex:/^[1-7]\|[0-2][0-9]:[0-5][0-9]$/'],
        ], [
            'slots.*.regex' => 'Se detecto un horario invalido en la seleccion.',
        ]);

        $slots = collect($data['slots'] ?? [])->unique()->values();

        $payload = $slots
            ->map(function (string $slot) use ($doctor) {
                [$dayOfWeek, $startTime] = explode('|', $slot);

                return [
                    'doctor_id' => $doctor->id,
                    'day_of_week' => (int) $dayOfWeek,
                    'start_time' => $startTime,
                    'end_time' => Carbon::createFromFormat('H:i', $startTime)->addMinutes(15)->format('H:i'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })
            ->all();

        $doctor->availabilities()->delete();

        if (!empty($payload)) {
            $doctor->availabilities()->insert($payload);
        }

        session()->flash('swal', [
            'title' => '¡Exito!',
            'text' => 'Disponibilidad del doctor guardada correctamente.',
            'icon' => 'success',
        ]);

        return redirect()->route('admin.doctors.availability.edit', $doctor);
    }

    /**
     * @return array<int, string>
     */
    private function weekdays(): array
    {
        return [
            1 => 'Lunes',
            2 => 'Martes',
            3 => 'Miercoles',
            4 => 'Jueves',
            5 => 'Viernes',
            6 => 'Sabado',
            7 => 'Domingo',
        ];
    }

    /**
     * @return Collection<int, array{label: string, slots: array<int, string>}>
     */
    private function hourGroups(): Collection
    {
        $groups = collect();

        for ($hour = 8; $hour <= 18; $hour++) {
            $hourLabel = str_pad((string) $hour, 2, '0', STR_PAD_LEFT) . ':00:00';
            $slots = [];

            foreach ([0, 15, 30, 45] as $minute) {
                if ($hour === 18 && $minute > 0) {
                    continue;
                }

                $slots[] = str_pad((string) $hour, 2, '0', STR_PAD_LEFT) . ':' . str_pad((string) $minute, 2, '0', STR_PAD_LEFT);
            }

            $groups->push([
                'label' => $hourLabel,
                'slots' => $slots,
            ]);
        }

        return $groups;
    }
}

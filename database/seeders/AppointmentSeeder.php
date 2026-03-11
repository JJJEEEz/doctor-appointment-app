<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AppointmentSeeder extends Seeder
{
    public function run(): void
    {
        $patients = Patient::query()->pluck('id');

        $doctors = Doctor::query()
            ->with('availabilities')
            ->get()
            ->filter(fn (Doctor $doctor) => $doctor->availabilities->isNotEmpty())
            ->values();

        if ($patients->isEmpty()) {
            $this->command?->warn('AppointmentSeeder: no hay pacientes existentes. No se generaron citas.');
            return;
        }

        if ($doctors->isEmpty()) {
            $this->command?->warn('AppointmentSeeder: no hay doctores con disponibilidad cargada. No se generaron citas.');
            return;
        }

        $targetAppointments = 40;
        $created = 0;
        $maxAttempts = 800;

        for ($attempt = 0; $attempt < $maxAttempts && $created < $targetAppointments; $attempt++) {
            /** @var Doctor $doctor */
            $doctor = $doctors->random();
            $patientId = (int) $patients->random();

            $date = Carbon::today()->addDays(random_int(0, 20));
            $daySlots = $doctor->availabilities
                ->where('day_of_week', $date->isoWeekday())
                ->keyBy(fn ($slot) => substr((string) $slot->start_time, 0, 5));

            if ($daySlots->isEmpty()) {
                continue;
            }

            $candidateStarts = [];

            foreach ($daySlots as $slotStart => $slot) {
                $slotStartTime = Carbon::createFromFormat('H:i', $slotStart);

                // Generate appointments aligned with UI options (:00 / :30).
                if (!in_array((int) $slotStartTime->format('i'), [0, 30], true)) {
                    continue;
                }

                $nextSlotStart = $slotStartTime->copy()->addMinutes(15)->format('H:i');
                $endTime = $slotStartTime->copy()->addMinutes(30)->format('H:i');

                if ($endTime > '20:00') {
                    continue;
                }

                if (!$daySlots->has($nextSlotStart)) {
                    continue;
                }

                $candidateStarts[] = $slotStart;
            }

            if (empty($candidateStarts)) {
                continue;
            }

            $startTime = $candidateStarts[array_rand($candidateStarts)];
            $endTime = Carbon::createFromFormat('H:i', $startTime)->addMinutes(30)->format('H:i');
            $dateString = $date->format('Y-m-d');

            $doctorConflict = Appointment::query()
                ->where('doctor_id', $doctor->id)
                ->whereDate('appointment_date', $dateString)
                ->where('status', '!=', 'Cancelado')
                ->where('start_time', '<', $endTime)
                ->where('end_time', '>', $startTime)
                ->exists();

            if ($doctorConflict) {
                continue;
            }

            $patientConflict = Appointment::query()
                ->where('patient_id', $patientId)
                ->whereDate('appointment_date', $dateString)
                ->where('status', '!=', 'Cancelado')
                ->where('start_time', '<', $endTime)
                ->where('end_time', '>', $startTime)
                ->exists();

            if ($patientConflict) {
                continue;
            }

            Appointment::query()->create([
                'patient_id' => $patientId,
                'doctor_id' => $doctor->id,
                'appointment_date' => $dateString,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'status' => $this->randomStatus(),
                'notes' => fake()->optional(0.7)->sentence(),
            ]);

            $created++;
        }

        $this->command?->info("AppointmentSeeder: se generaron {$created} citas.");
    }

    private function randomStatus(): string
    {
        $weight = random_int(1, 100);

        return match (true) {
            $weight <= 70 => 'Programado',
            $weight <= 90 => 'Completado',
            default => 'Cancelado',
        };
    }
}

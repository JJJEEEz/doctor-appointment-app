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
            $status = $this->randomStatus();

            $payload = [
                'patient_id' => $patientId,
                'doctor_id' => $doctor->id,
                'date' => $dateString,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'duration' => 30,
                'reason' => fake()->sentence(10),
                'status' => $status,
                'diagnosis' => null,
                'treatment' => null,
                'consultation_notes' => null,
                'prescriptions' => null,
            ];

            if ($status === Appointment::STATUS_COMPLETED) {
                $payload['diagnosis'] = fake()->sentence(6);
                $payload['treatment'] = fake()->sentence(10);
                $payload['consultation_notes'] = fake()->sentence(12);
                $payload['prescriptions'] = [
                    [
                        'medicine' => fake()->randomElement(['Amoxicilina 500mg', 'Ibuprofeno 400mg', 'Loratadina 10mg', 'Paracetamol 500mg']),
                        'dose' => fake()->randomElement(['1 cada 8h', '1 cada 12h', '1 cada 24h']),
                        'frequency' => fake()->randomElement(['Durante 3 dias', 'Durante 5 dias', 'Durante 7 dias']),
                    ],
                ];
            }

            $doctorConflict = Appointment::query()
                ->where('doctor_id', $doctor->id)
                ->whereDate('date', $dateString)
                ->where('status', '!=', Appointment::STATUS_CANCELLED)
                ->where('start_time', '<', $endTime)
                ->where('end_time', '>', $startTime)
                ->exists();

            if ($doctorConflict) {
                continue;
            }

            $patientConflict = Appointment::query()
                ->where('patient_id', $patientId)
                ->whereDate('date', $dateString)
                ->where('status', '!=', Appointment::STATUS_CANCELLED)
                ->where('start_time', '<', $endTime)
                ->where('end_time', '>', $startTime)
                ->exists();

            if ($patientConflict) {
                continue;
            }

            Appointment::query()->create($payload);

            $created++;
        }

        $this->normalizeConsultationData();

        $this->command?->info("AppointmentSeeder: se generaron {$created} citas.");
    }

    private function normalizeConsultationData(): void
    {
        Appointment::query()
            ->where('status', Appointment::STATUS_COMPLETED)
            ->where(function ($query) {
                $query
                    ->whereNull('diagnosis')
                    ->orWhereNull('treatment')
                    ->orWhereNull('consultation_notes')
                    ->orWhereNull('prescriptions');
            })
            ->get()
            ->each(function (Appointment $appointment) {
                $appointment->update([
                    'diagnosis' => $appointment->diagnosis ?: fake()->sentence(6),
                    'treatment' => $appointment->treatment ?: fake()->sentence(10),
                    'consultation_notes' => $appointment->consultation_notes ?: fake()->sentence(12),
                    'prescriptions' => $appointment->prescriptions ?: [[
                        'medicine' => fake()->randomElement(['Amoxicilina 500mg', 'Ibuprofeno 400mg', 'Loratadina 10mg', 'Paracetamol 500mg']),
                        'dose' => fake()->randomElement(['1 cada 8h', '1 cada 12h', '1 cada 24h']),
                        'frequency' => fake()->randomElement(['Durante 3 dias', 'Durante 5 dias', 'Durante 7 dias']),
                    ]],
                ]);
            });

        Appointment::query()
            ->where('status', '!=', Appointment::STATUS_COMPLETED)
            ->update([
                'diagnosis' => null,
                'treatment' => null,
                'consultation_notes' => null,
                'prescriptions' => null,
            ]);
    }

    private function randomStatus(): int
    {
        $weight = random_int(1, 100);

        return match (true) {
            $weight <= 70 => Appointment::STATUS_SCHEDULED,
            $weight <= 90 => Appointment::STATUS_COMPLETED,
            default => Appointment::STATUS_CANCELLED,
        };
    }
}

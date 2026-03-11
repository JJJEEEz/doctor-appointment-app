<?php

namespace Database\Seeders;

use App\Models\Doctor;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DoctorAvailabilitySeeder extends Seeder
{
    public function run(): void
    {
        $doctorIds = Doctor::query()->pluck('id');

        if ($doctorIds->isEmpty()) {
            $this->command?->warn('DoctorAvailabilitySeeder: no hay doctores existentes.');
            return;
        }

        $rows = [];

        foreach ($doctorIds as $doctorId) {
            for ($day = 1; $day <= 5; $day++) {
                $cursor = Carbon::createFromFormat('H:i', '08:00');

                while ($cursor->lt(Carbon::createFromFormat('H:i', '20:00'))) {
                    $start = $cursor->format('H:i');
                    $end = $cursor->copy()->addMinutes(15)->format('H:i');

                    if ($end > '20:00') {
                        break;
                    }

                    $rows[] = [
                        'doctor_id' => $doctorId,
                        'day_of_week' => $day,
                        'start_time' => $start,
                        'end_time' => $end,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    $cursor->addMinutes(15);
                }
            }
        }

        DB::table('doctor_availabilities')->upsert(
            $rows,
            ['doctor_id', 'day_of_week', 'start_time', 'end_time'],
            ['updated_at']
        );

        $this->command?->info('DoctorAvailabilitySeeder: disponibilidades generadas/actualizadas correctamente.');
    }
}

<?php

namespace App\Console\Commands;

use App\Mail\DailyAppointmentsReportMail;
use App\Models\Appointment;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;

class SendDailyAppointmentsReport extends Command
{
    protected $signature = 'appointments:send-daily-report';

    protected $description = 'Envia el reporte de citas del dia al administrador y a cada doctor';

    public function handle(): int
    {
        $reportDate = Carbon::today();

        $appointments = Appointment::query()
            ->with(['patient', 'doctor.user'])
            ->whereDate('date', $reportDate->toDateString())
            ->orderBy('start_time')
            ->get();

        $this->sendReportToAdmins($appointments, $reportDate);
        $this->sendReportToDoctors($appointments, $reportDate);

        $this->info('Reporte diario de citas enviado.');

        return self::SUCCESS;
    }

    private function sendReportToAdmins(Collection $appointments, CarbonInterface $reportDate): void
    {
        $adminEmails = User::query()
            ->role('Administrador')
            ->pluck('email')
            ->filter()
            ->unique()
            ->values();

        if ($adminEmails->isEmpty()) {
            $this->warn('No hay correos de administradores para enviar reporte.');

            return;
        }

        foreach ($adminEmails as $email) {
            Mail::to($email)->send(new DailyAppointmentsReportMail($reportDate, $appointments));
        }

        $this->line('Reporte enviado a '.$adminEmails->count().' administrador(es).');
    }

    private function sendReportToDoctors(Collection $appointments, CarbonInterface $reportDate): void
    {
        if ($appointments->isEmpty()) {
            $this->line('No hay citas para enviar a doctores.');

            return;
        }

        $sent = 0;

        foreach ($appointments->groupBy('doctor_id') as $doctorAppointments) {
            $doctor = $doctorAppointments->first()?->doctor;
            $doctorEmail = $doctor?->user?->email;

            if (! $doctor || ! $doctorEmail) {
                continue;
            }

            Mail::to($doctorEmail)->send(new DailyAppointmentsReportMail($reportDate, $doctorAppointments->values(), $doctor));
            $sent++;
        }

        $this->line('Reporte enviado a '.$sent.' doctor(es).');
    }
}

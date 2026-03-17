<?php

namespace App\Jobs;

use App\Models\Appointment;
use App\Services\WhatsAppService;
use Carbon\CarbonInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendAppointmentConfirmationJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 3;

    public function __construct(public readonly int $appointmentId)
    {
    }

    public function handle(WhatsAppService $whatsAppService): void
    {
        $appointment = Appointment::query()
            ->with(['patient', 'doctor.user'])
            ->find($this->appointmentId);

        if (! $appointment || $appointment->whatsapp_confirmation_sent_at !== null) {
            return;
        }

        if ($appointment->status !== Appointment::STATUS_SCHEDULED) {
            return;
        }

        $phone = (string) ($appointment->patient?->phone ?? '');

        if ($phone === '') {
            return;
        }

        $patientName = (string) ($appointment->patient?->name ?? 'Paciente');
        $doctorName = (string) ($appointment->doctor?->user?->name ?? 'Doctor');
        $date = $appointment->date instanceof CarbonInterface
            ? $appointment->date->format('Y-m-d')
            : (string) $appointment->date;

        $message = "Hola {$patientName}, tu cita con {$doctorName} fue confirmada para {$date} a las {$appointment->start_time}.";

        if ($whatsAppService->sendMessage($phone, $message)) {
            $appointment->forceFill([
                'whatsapp_confirmation_sent_at' => now(),
            ])->saveQuietly();
        }
    }
}

<?php

namespace App\Mail;

use App\Models\Doctor;
use Carbon\CarbonInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class DailyAppointmentsReportMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public CarbonInterface $reportDate,
        public Collection $appointments,
        public ?Doctor $doctor = null,
    ) {
    }

    public function envelope(): Envelope
    {
        $baseSubject = 'Reporte diario de citas - '.$this->reportDate->format('d/m/Y');

        if ($this->doctor && $this->doctor->relationLoaded('user') && $this->doctor->user) {
            $baseSubject .= ' (Dr. '.$this->doctor->user->name.')';
        }

        return new Envelope(subject: $baseSubject);
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.daily-appointments-report',
            with: [
                'reportDate' => $this->reportDate,
                'appointments' => $this->appointments,
                'doctor' => $this->doctor,
            ],
        );
    }
}

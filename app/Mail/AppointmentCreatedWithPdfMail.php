<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppointmentCreatedWithPdfMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public Appointment $appointment,
        private readonly string $pdfContent,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Comprobante de cita medica',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.appointment-created',
            with: [
                'appointment' => $this->appointment,
            ],
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromData(fn () => $this->pdfContent, $this->fileName())
                ->withMime('application/pdf'),
        ];
    }

    private function fileName(): string
    {
        $date = optional($this->appointment->date)->format('Y-m-d') ?? now()->format('Y-m-d');

        return sprintf('comprobante-cita-%s.pdf', $date);
    }
}

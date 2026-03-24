<?php

namespace App\Services;

use App\Models\Appointment;
use Barryvdh\DomPDF\Facade\Pdf;

class AppointmentPdfService
{
    public function generateConfirmationPdf(Appointment $appointment): string
    {
        $appointment->loadMissing(['patient', 'doctor.user', 'doctor.speciality']);

        return Pdf::loadView('pdf.appointment-confirmation', [
            'appointment' => $appointment,
        ])->setPaper('a4')->output();
    }
}

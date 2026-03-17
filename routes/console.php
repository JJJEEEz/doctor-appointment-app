<?php

use App\Jobs\SendAppointmentReminderJob;
use App\Models\Appointment;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('appointments:send-reminders', function () {
    $tomorrow = now()->addDay()->toDateString();

    $appointments = Appointment::query()
        ->whereDate('date', $tomorrow)
        ->where('status', Appointment::STATUS_SCHEDULED)
        ->whereNull('whatsapp_reminder_sent_at')
        ->pluck('id');

    $sent = 0;

    foreach ($appointments as $appointmentId) {
        SendAppointmentReminderJob::dispatchSync((int) $appointmentId);
        $sent++;
    }

    $this->info("Recordatorios procesados: {$sent}");
})->purpose('Send WhatsApp reminders for appointments scheduled for tomorrow');

Schedule::command('appointments:send-reminders')
    ->dailyAt(env('WHATSAPP_REMINDER_TIME', '09:00'));

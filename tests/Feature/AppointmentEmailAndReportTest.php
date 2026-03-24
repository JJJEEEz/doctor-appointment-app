<?php

use App\Mail\AppointmentCreatedWithPdfMail;
use App\Mail\DailyAppointmentsReportMail;
use App\Models\Appointment;
use App\Models\BloodType;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;
use App\Services\AppointmentPdfService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;

it('sends appointment confirmation email with pdf to patient and doctor when appointment is created', function () {
    Mail::fake();

    $this->mock(AppointmentPdfService::class, function ($mock) {
        $mock->shouldReceive('generateConfirmationPdf')
            ->once()
            ->andReturn('fake-pdf-content');
    });

    $authUser = User::factory()->create();
    $this->actingAs($authUser);

    $bloodTypeId = DB::table('blood_types')->insertGetId([
        'name' => 'A+',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    $bloodType = BloodType::query()->findOrFail($bloodTypeId);

    $patientUser = User::factory()->create(['email' => 'patient.user@example.com']);
    $patient = Patient::query()->create([
        'user_id' => $patientUser->id,
        'blood_type_id' => $bloodType->id,
        'name' => 'Paciente Demo',
        'email' => 'patient@example.com',
        'phone' => '5551234567',
        'address' => 'Direccion 123',
    ]);

    $doctorUser = User::factory()->create(['email' => 'doctor@example.com']);
    $doctor = Doctor::query()->create([
        'user_id' => $doctorUser->id,
        'speciality_id' => null,
        'medical_license_number' => 'MED-1234',
        'biography' => null,
    ]);

    $response = $this->post(route('admin.appointments.store'), [
        'patient_id' => $patient->id,
        'doctor_id' => $doctor->id,
        'date' => now()->addDay()->toDateString(),
        'start_time' => '10:00',
        'end_time' => '10:30',
        'reason' => 'Chequeo general',
        'status' => Appointment::STATUS_SCHEDULED,
    ]);

    $response->assertRedirect(route('admin.appointments.index'));

    Mail::assertSent(AppointmentCreatedWithPdfMail::class, 2);

    Mail::assertSent(AppointmentCreatedWithPdfMail::class, function (AppointmentCreatedWithPdfMail $mail) {
        return $mail->hasTo('patient@example.com');
    });

    Mail::assertSent(AppointmentCreatedWithPdfMail::class, function (AppointmentCreatedWithPdfMail $mail) {
        return $mail->hasTo('doctor@example.com');
    });
});

it('sends daily report to admins and doctors', function () {
    Mail::fake();

    Role::findOrCreate('Administrador');

    $admin = User::factory()->create(['email' => 'admin@example.com']);
    $admin->assignRole('Administrador');

    $bloodTypeId = DB::table('blood_types')->insertGetId([
        'name' => 'B+',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    $bloodType = BloodType::query()->findOrFail($bloodTypeId);

    $doctorUserA = User::factory()->create(['email' => 'doctor.a@example.com']);
    $doctorA = Doctor::query()->create([
        'user_id' => $doctorUserA->id,
        'speciality_id' => null,
        'medical_license_number' => 'MED-A',
        'biography' => null,
    ]);

    $doctorUserB = User::factory()->create(['email' => 'doctor.b@example.com']);
    $doctorB = Doctor::query()->create([
        'user_id' => $doctorUserB->id,
        'speciality_id' => null,
        'medical_license_number' => 'MED-B',
        'biography' => null,
    ]);

    $patientUserA = User::factory()->create(['email' => 'patient.a.user@example.com']);
    $patientA = Patient::query()->create([
        'user_id' => $patientUserA->id,
        'blood_type_id' => $bloodType->id,
        'name' => 'Paciente A',
        'email' => 'patient.a@example.com',
        'phone' => '5550000001',
        'address' => 'Direccion A',
    ]);

    $patientUserB = User::factory()->create(['email' => 'patient.b.user@example.com']);
    $patientB = Patient::query()->create([
        'user_id' => $patientUserB->id,
        'blood_type_id' => $bloodType->id,
        'name' => 'Paciente B',
        'email' => 'patient.b@example.com',
        'phone' => '5550000002',
        'address' => 'Direccion B',
    ]);

    Appointment::query()->create([
        'patient_id' => $patientA->id,
        'doctor_id' => $doctorA->id,
        'date' => now()->toDateString(),
        'start_time' => '08:00',
        'end_time' => '08:30',
        'duration' => 30,
        'reason' => 'Consulta A',
        'status' => Appointment::STATUS_SCHEDULED,
    ]);

    Appointment::query()->create([
        'patient_id' => $patientB->id,
        'doctor_id' => $doctorB->id,
        'date' => now()->toDateString(),
        'start_time' => '09:00',
        'end_time' => '09:30',
        'duration' => 30,
        'reason' => 'Consulta B',
        'status' => Appointment::STATUS_SCHEDULED,
    ]);

    $this->artisan('appointments:send-daily-report')->assertSuccessful();

    Mail::assertSent(DailyAppointmentsReportMail::class, 3);

    Mail::assertSent(DailyAppointmentsReportMail::class, fn (DailyAppointmentsReportMail $mail) => $mail->hasTo('admin@example.com'));
    Mail::assertSent(DailyAppointmentsReportMail::class, fn (DailyAppointmentsReportMail $mail) => $mail->hasTo('doctor.a@example.com'));
    Mail::assertSent(DailyAppointmentsReportMail::class, fn (DailyAppointmentsReportMail $mail) => $mail->hasTo('doctor.b@example.com'));
});

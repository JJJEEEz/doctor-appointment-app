<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->unsignedInteger('duration')->default(15);
            $table->text('reason')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->text('diagnosis')->nullable();
            $table->text('treatment')->nullable();
            $table->text('consultation_notes')->nullable();
            $table->json('prescriptions')->nullable();
            $table->timestamps();

            $table->index([
                'doctor_id',
                'date',
                'start_time',
                'end_time',
                'status',
            ], 'appointments_doctor_schedule_index');

            $table->index([
                'patient_id',
                'date',
                'start_time',
                'end_time',
            ], 'appointments_patient_schedule_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};

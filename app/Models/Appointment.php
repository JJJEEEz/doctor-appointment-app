<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
    public const STATUS_SCHEDULED = 1;
    public const STATUS_COMPLETED = 2;
    public const STATUS_CANCELLED = 0;

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'date',
        'start_time',
        'end_time',
        'duration',
        'reason',
        'status',
        'diagnosis',
        'treatment',
        'consultation_notes',
        'prescriptions',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'status' => 'integer',
            'duration' => 'integer',
            'prescriptions' => 'array',
        ];
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            self::STATUS_CANCELLED => 'Cancelada',
            self::STATUS_COMPLETED => 'Completada',
            default => 'Programada',
        };
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }
}

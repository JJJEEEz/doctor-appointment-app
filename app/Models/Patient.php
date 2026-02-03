<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Patient extends Model
{
    protected $fillable = [
        'user_id',
        'blood_type_id',
        'name',
        'email',
        'phone',
        'address',
    ];

    /**
     * Relación 1 a 1 con User
     * Un paciente pertenece a un usuario
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Tipo de sangre del paciente
     */
    public function bloodType(): BelongsTo
    {
        return $this->belongsTo(BloodType::class);
    }
}

<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\DoctorAvailability;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class AppointmentConflictService
{
    public function hasAvailability(Doctor $doctor, Carbon $date, string $startTime, string $endTime): bool
    {
        if (!$this->hasAvailabilityConfiguration()) {
            return true;
        }

        $requiredSlots = $this->requiredSlots($startTime, $endTime);

        if (empty($requiredSlots)) {
            return false;
        }

        $dayOfWeek = $date->isoWeekday();

        $availableSlots = $doctor->availabilities()
            ->where('day_of_week', $dayOfWeek)
            ->get(['start_time', 'end_time'])
            ->keyBy(function ($slot) {
                return substr((string) $slot->start_time, 0, 8);
            });

        foreach ($requiredSlots as $slotStart => $slotEnd) {
            $availability = $availableSlots->get($slotStart);

            if (!$availability) {
                return false;
            }

            if (substr((string) $availability->end_time, 0, 8) !== $slotEnd) {
                return false;
            }
        }

        return true;
    }

    public function doctorHasConflict(int $doctorId, string $date, string $startTime, string $endTime, ?int $ignoreAppointmentId = null): bool
    {
        $dbStartTime = $this->toDbTime($startTime);
        $dbEndTime = $this->toDbTime($endTime);

        $query = Appointment::query()
            ->where('doctor_id', $doctorId)
            ->whereDate('appointment_date', $date)
            ->where('status', '!=', 'Cancelado')
            ->where('start_time', '<', $dbEndTime)
            ->where('end_time', '>', $dbStartTime);

        if ($ignoreAppointmentId) {
            $query->where('id', '!=', $ignoreAppointmentId);
        }

        return $query->exists();
    }

    public function patientHasConflict(int $patientId, string $date, string $startTime, string $endTime, ?int $ignoreAppointmentId = null): bool
    {
        $dbStartTime = $this->toDbTime($startTime);
        $dbEndTime = $this->toDbTime($endTime);

        $query = Appointment::query()
            ->where('patient_id', $patientId)
            ->whereDate('appointment_date', $date)
            ->where('status', '!=', 'Cancelado')
            ->where('start_time', '<', $dbEndTime)
            ->where('end_time', '>', $dbStartTime);

        if ($ignoreAppointmentId) {
            $query->where('id', '!=', $ignoreAppointmentId);
        }

        return $query->exists();
    }

    public function availableDoctors(string $date, string $startTime, string $endTime, ?int $specialityId = null): Builder
    {
        $dbStartTime = $this->toDbTime($startTime);
        $dbEndTime = $this->toDbTime($endTime);

        $query = Doctor::query()
            ->with(['user', 'speciality'])
            ->when($specialityId, function (Builder $query) use ($specialityId) {
                $query->where('speciality_id', $specialityId);
            })
            ->whereDoesntHave('appointments', function (Builder $query) use ($date, $dbStartTime, $dbEndTime) {
                $query
                    ->whereDate('appointment_date', $date)
                    ->where('status', '!=', 'Cancelado')
                    ->where('start_time', '<', $dbEndTime)
                    ->where('end_time', '>', $dbStartTime);
            });

        if (!$this->hasAvailabilityConfiguration()) {
            return $query;
        }

        $carbonDate = Carbon::createFromFormat('Y-m-d', $date);
        $requiredSlots = $this->requiredSlots($startTime, $endTime);

        return $query->whereHas('availabilities', function (Builder $query) use ($carbonDate, $requiredSlots) {
            $query
                ->where('day_of_week', $carbonDate->isoWeekday())
                ->where(function (Builder $slotQuery) use ($requiredSlots) {
                    foreach ($requiredSlots as $slotStart => $slotEnd) {
                        $slotQuery->orWhere(function (Builder $singleSlotQuery) use ($slotStart, $slotEnd) {
                            $singleSlotQuery
                                ->where('start_time', $slotStart)
                                ->where('end_time', $slotEnd);
                        });
                    }
                });
        }, '=', count($requiredSlots));
    }

    /**
     * @return array<string, string>
     */
    public function requiredSlots(string $startTime, string $endTime): array
    {
        $start = Carbon::createFromFormat('H:i', substr($startTime, 0, 5));
        $end = Carbon::createFromFormat('H:i', substr($endTime, 0, 5));

        if ($end->lessThanOrEqualTo($start)) {
            return [];
        }

        $slots = [];
        $cursor = $start->copy();

        while ($cursor->lessThan($end)) {
            $slotStart = $cursor->format('H:i:s');
            $slotEnd = $cursor->copy()->addMinutes(15)->format('H:i:s');

            if (Carbon::createFromFormat('H:i:s', $slotEnd)->greaterThan($end)) {
                return [];
            }

            $slots[$slotStart] = $slotEnd;
            $cursor->addMinutes(15);
        }

        return $slots;
    }

    private function toDbTime(string $time): string
    {
        return Carbon::createFromFormat('H:i', substr($time, 0, 5))->format('H:i:s');
    }

    private function hasAvailabilityConfiguration(): bool
    {
        static $hasAvailabilityConfiguration;

        if ($hasAvailabilityConfiguration === null) {
            $hasAvailabilityConfiguration = DoctorAvailability::query()->exists();
        }

        return $hasAvailabilityConfiguration;
    }
}

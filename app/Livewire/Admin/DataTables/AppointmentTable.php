<?php

namespace App\Livewire\Admin\DataTables;

use App\Models\Appointment;
use Livewire\Component;
use Livewire\WithPagination;

class AppointmentTable extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $appointments = Appointment::query()
            ->with(['patient', 'doctor.user'])
            ->when($this->search, function ($query) {
                $search = '%' . $this->search . '%';

                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('status', 'like', $search)
                        ->orWhere('appointment_date', 'like', $search)
                        ->orWhereHas('patient', function ($patientQuery) use ($search) {
                            $patientQuery
                                ->where('name', 'like', $search)
                                ->orWhere('email', 'like', $search);
                        })
                        ->orWhereHas('doctor.user', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'like', $search);
                        });
                });
            })
            ->orderByDesc('appointment_date')
            ->orderByDesc('start_time')
            ->paginate(10);

        return view('livewire.admin.datatables.appointment-table', [
            'appointments' => $appointments,
        ]);
    }
}

<?php

namespace App\Livewire\Admin\Datatables;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Patient;

class PatientTable extends Component
{
    use WithPagination;

    public $search = '';

    public function render()
    {
        $patients = Patient::query()
            ->with('bloodType')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                    ->orWhere('phone', 'like', '%' . $this->search . '%');
            })
            ->paginate(10);

        return view('livewire.admin.datatables.patient-table', [
            'patients' => $patients
        ]);
    }

    public function delete($patientId)
    {
        $patient = Patient::findOrFail($patientId);
        $patient->delete();

        session()->flash('message', 'Paciente eliminado exitosamente.');
    }
}

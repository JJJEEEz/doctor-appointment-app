<?php

namespace App\Livewire\Admin;

use App\Models\Appointment;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Consulta')]
class ConsultationManager extends Component
{
    public $appointment;
    public $showPatientHistoryModal = false;
    public $showPreviousConsultationsModal = false;
    public $activeTab = 'consultation';
    public $diagnosis = '';
    public $treatment = '';
    public $consultationNotes = '';
    public $prescriptions = [];

    public function mount($appointment)
    {
        // Si viene como string, resolver el modelo
        if (is_string($appointment)) {
            $appointment = Appointment::findOrFail($appointment);
        }
        
        $this->appointment = $appointment->load(['doctor.user', 'patient.bloodType']);
        $this->diagnosis = $appointment->diagnosis ?? '';
        $this->treatment = $appointment->treatment ?? '';
        $this->consultationNotes = $appointment->consultation_notes ?? '';
        $this->prescriptions = $appointment->prescriptions ?? [];
    }

    public function togglePatientHistoryModal()
    {
        $this->showPatientHistoryModal = !$this->showPatientHistoryModal;
    }

    public function togglePreviousConsultationsModal()
    {
        $this->showPreviousConsultationsModal = !$this->showPreviousConsultationsModal;
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function addMedication()
    {
        $this->prescriptions[] = ['medicine' => '', 'dose' => '', 'frequency' => ''];
    }

    public function removeMedication($index)
    {
        unset($this->prescriptions[$index]);
        $this->prescriptions = array_values($this->prescriptions);
    }

    public function save()
    {
        $this->appointment->update([
            'diagnosis' => $this->diagnosis,
            'treatment' => $this->treatment,
            'consultation_notes' => $this->consultationNotes,
            'prescriptions' => $this->prescriptions,
            'status' => 2, // STATUS_COMPLETED
        ]);

        session()->flash('swal', [
            'title' => 'Exito',
            'text' => 'Consulta guardada correctamente.',
            'icon' => 'success',
        ]);

        return redirect()->route('admin.appointments.index');
    }

    public function previousConsultations()
    {
        return Appointment::query()
            ->with('doctor.user')
            ->where('patient_id', $this->appointment->patient_id)
            ->where('id', '!=', $this->appointment->id)
            ->where(function ($query) {
                $query->whereNotNull('diagnosis')->orWhereNotNull('treatment');
            })
            ->orderByDesc('date')
            ->orderByDesc('start_time')
            ->get();
    }

    public function render()
    {
        return view('livewire.admin.consultation-manager', [
            'previousConsultations' => $this->previousConsultations(),
        ]);
    }
}

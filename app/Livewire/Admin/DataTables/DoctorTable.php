<?php

namespace App\Livewire\Admin\Datatables;

use App\Models\Doctor;
use Livewire\Component;
use Livewire\WithPagination;

class DoctorTable extends Component
{
    use WithPagination;

    public $search = '';

    public function render()
    {
        $doctors = Doctor::query()
            ->with(['user', 'speciality'])
            ->when($this->search, function ($query) {
                $query->where(function ($subQuery) {
                    $search = '%' . $this->search . '%';

                    $subQuery
                        ->where('medical_license_number', 'like', $search)
                        ->orWhere('biography', 'like', $search)
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery
                                ->where('name', 'like', $search)
                                ->orWhere('email', 'like', $search);
                        });
                });
            })
            ->paginate(10);

        return view('livewire.admin.datatables.doctor-table', [
            'doctors' => $doctors,
        ]);
    }
}

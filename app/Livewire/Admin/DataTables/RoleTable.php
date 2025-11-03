<?php

namespace App\Livewire\Admin\Datatables;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class RoleTable extends Component
{
    use WithPagination;

    public $search = '';

    public function render()
    {
        $roles = Role::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->paginate(10);

        return view('livewire.admin.datatables.role-table', [
            'roles' => $roles
        ]);
    }

    public function delete($roleId)
    {
        $role = Role::findOrFail($roleId);
        $role->delete();

        session()->flash('message', 'Rol eliminado exitosamente.');
    }
}
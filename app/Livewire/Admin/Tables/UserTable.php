<?php

namespace App\Livewire\Admin\Tables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class UserTable extends DataTableComponent
{
    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }
    public function builder(): Builder
    {
        return User::with('roles');
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')->sortable(),
            Column::make('Nombre', 'name')->sortable(),
            Column::make('Correo', 'email')->sortable(),
            Column::make('Número ID', 'id_number'),
            Column::make('Teléfono', 'phone'),
            Column::make('Rol')->label(fn($row) => $row->roles->first()?->name ?? 'Sin rol'),
            Column::make('Acciones')->label(fn($row) => view('admin.users.actions', ['user' => $row])),
        ];
    }
}

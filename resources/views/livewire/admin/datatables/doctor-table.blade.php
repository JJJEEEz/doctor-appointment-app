<div>
    <div class="overflow-x-auto mt-4">
        <table class="min-w-full bg-white border border-gray-200">
            <thead>
                <tr>
                    <th class="px-4 py-2 border">ID</th>
                    <th class="px-4 py-2 border">Nombre</th>
                    <th class="px-4 py-2 border">Especialidad</th>
                    <th class="px-4 py-2 border">Cedula</th>
                    <th class="px-4 py-2 border">Biografia</th>
                    <th class="px-4 py-2 border">Creado</th>
                    <th class="px-4 py-2 border">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($doctors as $doctor)
                    <tr>
                        <td class="px-4 py-2 border">{{ $doctor->id }}</td>
                        <td class="px-4 py-2 border">{{ $doctor->user?->name ?? 'N/A' }}</td>
                        <td class="px-4 py-2 border">{{ $doctor->speciality?->name ?? 'N/A' }}</td>
                        <td class="px-4 py-2 border">{{ $doctor->medical_license_number ?: 'N/A' }}</td>
                        <td class="px-4 py-2 border">
                            {{ $doctor->biography ? \Illuminate\Support\Str::limit($doctor->biography, 80) : 'N/A' }}
                        </td>
                        <td class="px-4 py-2 border">{{ $doctor->created_at }}</td>
                        <td class="px-4 py-2 border">
                            @include('admin.doctors.actions', ['doctor' => $doctor])
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-2 border text-center">No hay doctores registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-2">
            {{ $doctors->links() }}
        </div>
    </div>
</div>

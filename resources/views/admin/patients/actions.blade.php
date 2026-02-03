<div class="flex items-center gap-2">
    {{-- Botón Ver --}}
    <a href="{{ route('admin.patients.show', $patient) }}" class="inline-flex items-center px-3 py-1 text-sm bg-green-500 hover:bg-green-600 text-white rounded transition">
        <i class="fas fa-eye mr-1"></i> Ver
    </a>

    {{-- Botón Editar --}}
    <a href="{{ route('admin.patients.edit', $patient) }}" class="inline-flex items-center px-3 py-1 text-sm bg-yellow-500 hover:bg-yellow-600 text-white rounded transition">
        <i class="fas fa-edit mr-1"></i> Editar
    </a>

    {{-- Botón Eliminar con confirmación SweetAlert --}}
    <form id="delete-patient-{{ $patient->id }}" action="{{ route('admin.patients.destroy', $patient) }}" method="POST" style="display:inline">
        @csrf
        @method('DELETE')
        <button type="button" class="inline-flex items-center px-3 py-1 text-sm bg-red-600 hover:bg-red-700 text-white rounded transition" onclick="confirmPatientDelete({{ $patient->id }}, '{{ $patient->name }}')">
            <i class="fas fa-trash mr-1"></i> Eliminar
        </button>
    </form>
</div>

@once
    @push('modals')
        <script>
        function confirmPatientDelete(patientId, patientName) {
            Swal.fire({
                title: '¿Eliminar paciente?',
                html: `¿Estás seguro de que deseas eliminar a <strong>${patientName}</strong>?<br><small class="text-gray-600">Esta acción no se puede deshacer.</small>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#6B7280',
                confirmButtonText: '<i class="fa fa-trash mr-2"></i>Sí, eliminar',
                cancelButtonText: '<i class="fa fa-times mr-2"></i>Cancelar',
                reverseButtons: true,
                focusCancel: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-patient-' + patientId).submit();
                }
            });
        }
        </script>
    @endpush
@endonce

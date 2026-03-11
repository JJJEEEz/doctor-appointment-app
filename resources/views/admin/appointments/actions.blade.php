<div class="flex items-center gap-2">
    <a href="{{ route('admin.appointments.consultation', $appointment) }}" class="inline-flex items-center px-3 py-1 text-sm bg-indigo-500 hover:bg-indigo-600 text-white rounded transition" title="Atender cita">
        <i class="fas fa-stethoscope mr-1"></i> Atender
    </a>

    <a href="{{ route('admin.appointments.show', $appointment) }}" class="inline-flex items-center px-3 py-1 text-sm bg-green-500 hover:bg-green-600 text-white rounded transition">
        <i class="fas fa-eye mr-1"></i> Ver
    </a>

    <a href="{{ route('admin.appointments.edit', $appointment) }}" class="inline-flex items-center px-3 py-1 text-sm bg-yellow-500 hover:bg-yellow-600 text-white rounded transition">
        <i class="fas fa-edit mr-1"></i> Editar
    </a>

    <form id="delete-appointment-{{ $appointment->id }}" action="{{ route('admin.appointments.destroy', $appointment) }}" method="POST" style="display:inline">
        @csrf
        @method('DELETE')
        <button type="button" class="inline-flex items-center px-3 py-1 text-sm bg-red-600 hover:bg-red-700 text-white rounded transition" onclick="confirmAppointmentDelete({{ $appointment->id }})">
            <i class="fas fa-trash mr-1"></i> Eliminar
        </button>
    </form>
</div>

@once
    @push('modals')
        <script>
        function confirmAppointmentDelete(appointmentId) {
            Swal.fire({
                title: '¿Eliminar cita?',
                text: 'Esta accion no se puede deshacer.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Si, eliminar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true,
                focusCancel: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-appointment-' + appointmentId).submit();
                }
            });
        }
        </script>
    @endpush
@endonce

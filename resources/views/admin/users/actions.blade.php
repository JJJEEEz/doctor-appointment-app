<div class="flex items-center gap-2">
    {{-- Botón Editar con WireUI --}}
    <x-button 
        href="{{ route('admin.users.edit', $user) }}" 
        xs
        warning 
        icon="pencil"
        label="Editar"
    />

    {{-- Botón Eliminar con confirmación SweetAlert --}}
    <form id="delete-user-{{ $user->id }}" action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display:inline">
        @csrf
        @method('DELETE')
        <x-button 
            type="button" 
            xs
            negative 
            icon="trash"
            label="Eliminar"
            onclick="confirmUserDelete({{ $user->id }}, '{{ $user->name }}')"
        />
    </form>
</div>

@once
    @push('modals')
        <script>
        function confirmUserDelete(userId, userName) {
            Swal.fire({
                title: '¿Eliminar usuario?',
                html: `¿Estás seguro de que deseas eliminar a <strong>${userName}</strong>?<br><small class="text-gray-600">Esta acción no se puede deshacer.</small>`,
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
                    document.getElementById('delete-user-' + userId).submit();
                }
            });
        }
        </script>
    @endpush
@endonce

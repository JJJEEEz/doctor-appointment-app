<div class="flex items-center gap-2">
    {{-- Botón Editar --}}
    <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex items-center px-3 py-1 text-sm bg-yellow-500 hover:bg-yellow-600 text-white rounded transition">
        <i class="fas fa-edit mr-1"></i> Editar
    </a>

    {{-- Botón Eliminar con confirmación SweetAlert --}}
    <form id="delete-user-{{ $user->id }}" action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display:inline">
        @csrf
        @method('DELETE')
        <button type="button" class="inline-flex items-center px-3 py-1 text-sm bg-red-600 hover:bg-red-700 text-white rounded transition" onclick="confirmUserDelete({{ $user->id }}, '{{ $user->name }}')">
            <i class="fas fa-trash mr-1"></i> Eliminar
        </button>
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

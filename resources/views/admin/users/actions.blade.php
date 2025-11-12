<div class="flex items-center gap-2">
    <a href="{{ route('admin.users.edit', $user) }}" class="px-2 py-1 bg-yellow-400 rounded">Editar</a>

    <form id="delete-user-{{ $user->id }}" action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display:inline">
        @csrf
        @method('DELETE')
        <button type="button" onclick="confirmUserDelete({{ $user->id }})" class="px-2 py-1 bg-red-500 text-white rounded">Eliminar</button>
    </form>
</div>

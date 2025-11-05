{{-- 
  Este es el contenido para:
  resources/views/admin/roles/actions.blade.php
--}}
<div class="flex items-center space-x-2">

    {{-- Botón de Editar (Azul) --}}
    <a href="{{ route('admin.roles.edit', $role) }}" 
       class="inline-flex items-center justify-center p-2 text-blue-600 bg-blue-100 hover:bg-blue-200 dark:text-blue-200 dark:bg-blue-700 dark:hover:bg-blue-600 rounded-lg transition-colors duration-150"
       title="Editar rol">
        
        <!-- Icono de Lápiz -->
        <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
        </svg>
    </a>

    {{-- Botón de Eliminar (Rojo) --}}
    <form id="delete-role-{{ $role->id }}" action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="inline">
        @csrf
        @method('DELETE')

        <button type="button"
                onclick="confirmRoleDelete({{ $role->id }})"
                class="inline-flex items-center justify-center p-2 text-red-600 bg-red-100 hover:bg-red-200 dark:text-red-200 dark:bg-red-700 dark:hover:bg-red-600 rounded-lg transition-colors duration-150"
                title="Eliminar rol">
        
        <!-- Icono de Basura -->
        <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12.576 0L3.055 5.79m12.576 0a48.108 48.108 0 0 1-3.478-.397m-9.098 0a48.108 48.108 0 0 1-3.478.397M5.79 5.79l14.456 0" />
        </svg>
        </button>
    </form>
</div>
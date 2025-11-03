<x-admin-layout 
    title="Roles"
    :breadcrumbs="[
        ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
        ['name' => 'Roles',     'href' => route('admin.roles.index')],
    ]"
>
    <section class="p-6 bg-gray-100 dark:bg-gray-800 rounded-lg shadow-lg">
        
        <div class="flex items-center justify-between mb-6">
            
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                Roles
            </h1>

        
            <a
                href="{{ route('admin.roles.create') }}"
                class="inline-flex items-center justify-center px-4 py-2 bg-gray-900 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-800 active:bg-gray-950 focus:outline-none focus:ring-2 focus:ring-gray-700 focus:ring-offset-2 dark:bg-gray-700 dark:hover:bg-gray-600 dark:active:bg-gray-800 dark:focus:ring-gray-500 dark:focus:ring-offset-gray-800 transition-colors duration-150"
            >
                <svg class="w-4 h-4 me-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                
                Nuevo
            </a>
        </div>

        @livewire('admin.datatables.role-table')
        
    </section>
</x-admin-layout>
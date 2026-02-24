<x-admin-layout
    title="Doctores"
    :breadcrumbs="[
        ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
        ['name' => 'Doctores', 'href' => route('admin.doctors.index')],
    ]"
>
    <section class="p-6 bg-gray-100 dark:bg-gray-800 rounded-lg shadow-lg">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                Doctores
            </h1>

            {{-- <x-wire-button href="{{ route('admin.doctors.create') }}">
                <svg class="w-4 h-4 me-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Nuevo
            </x-wire-button> --}}
        </div>

        @livewire('admin.datatables.doctor-table')
    </section>
</x-admin-layout>

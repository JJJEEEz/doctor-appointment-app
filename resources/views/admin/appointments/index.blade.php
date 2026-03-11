<x-admin-layout
    title="Citas"
    :breadcrumbs="[
        ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
        ['name' => 'Citas', 'href' => route('admin.appointments.index')],
    ]"
>
    <section class="p-6 bg-gray-100 dark:bg-gray-800 rounded-lg shadow-lg">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                Citas
            </h1>

            <x-wire-button href="{{ route('admin.appointments.create') }}">
                <i class="fa-solid fa-plus mr-2"></i>
                Nuevo
            </x-wire-button>
        </div>

        @livewire('admin.datatables.appointment-table')
    </section>
</x-admin-layout>

{{-- Usar la sintaxis recomendada para componentes de clase --}}
@php
    $title = $title ?? config('app.name', 'Laravel');
    $breadcrumbs = $breadcrumbs ?? [];
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://kit.fontawesome.com/a7de8752fc.js" crossorigin="anonymous"></script>

    <wireui:scripts />
        {{-- Livewire Scripts --}}
        @livewireScripts

        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="font-sans antialiased bg-gray-50">
        @include('layouts.includes.admin.navigation')

        @include('layouts.includes.admin.sidebar')

        <div class="p-4 sm:ml-64">
            <!-- Margin top 14px -->
            <div class="mt-14">
            @include('layouts.includes.admin.breadcrumb', ['breadcrumbs' => $breadcrumbs])
                {{ $slot }}
            </div>
        </div>

        @stack('modals')
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    <script src="https://unpkg.com/@rappasoft/laravel-livewire-tables@v2.2.0/resources/js/livewire-tables.js"></script>

    {{-- SweetAlert2 CDN (used for flash alerts from controllers) --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Render a SweetAlert from session key 'swal' if present --}}
    @if(session('swal'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire(@json(session('swal')));
            });
        </script>
    @endif

    {{-- Confirm delete helper used by role actions --}}
    <script>
        function confirmRoleDelete(roleId) {
            const form = document.getElementById('delete-role-' + roleId);
            if (!form) return;

            Swal.fire({
                title: '¿Estás seguro?',
                text: 'Esta acción eliminará el rol permanentemente.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    </script>

    </body>
</html>

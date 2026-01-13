@php
    $title = 'Crear Usuario';
    $breadcrumbs = [
        ['name' => 'Inicio', 'href' => route('dashboard')],
        ['name' => 'Usuarios', 'href' => route('admin.users.index')],
        ['name' => 'Crear Usuario'],
    ];
@endphp

@component('layouts.admin', ['title' => $title, 'breadcrumbs' => $breadcrumbs])
    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-2xl font-semibold mb-6 text-gray-800">Crear Usuario</h2>

        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Nombre --}}
                <div>
                    <x-input 
                        label="Nombre Completo *" 
                        name="name" 
                        placeholder="Ej: Juan Pérez"
                        value="{{ old('name') }}"
                        autocomplete="name"
                        icon="user"
                    />
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <x-input 
                        label="Email *" 
                        name="email" 
                        type="email"
                        placeholder="ejemplo@correo.com"
                        value="{{ old('email') }}"
                        autocomplete="email"
                        icon="mail"
                    />
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Número de Identificación --}}
                <div>
                    <x-input 
                        label="Número de Identificación *" 
                        name="id_number" 
                        placeholder="Ej: 12345678A, DNI, Pasaporte"
                        value="{{ old('id_number') }}"
                        icon="identification"
                        hint="DNI, Cédula, Pasaporte u otro documento"
                    />
                    @error('id_number')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Teléfono --}}
                <div>
                    <x-input 
                        label="Teléfono" 
                        name="phone" 
                        placeholder="Ej: +34 123 456 789"
                        value="{{ old('phone') }}"
                        autocomplete="tel"
                        icon="phone"
                    />
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Dirección --}}
            <div>
                <x-textarea 
                    label="Dirección" 
                    name="address" 
                    placeholder="Calle, número, ciudad, código postal..."
                    rows="3"
                >{{ old('address') }}</x-textarea>
                @error('address')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Contraseña --}}
                <div>
                    <x-inputs.password 
                        label="Contraseña *" 
                        name="password" 
                        placeholder="Mínimo 8 caracteres"
                        autocomplete="new-password"
                    />
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Confirmar Contraseña --}}
                <div>
                    <x-inputs.password 
                        label="Confirmar Contraseña *" 
                        name="password_confirmation" 
                        placeholder="Repite la contraseña"
                        autocomplete="new-password"
                    />
                </div>
            </div>

            {{-- Rol --}}
            <div>
                <x-select 
                    label="Rol del Usuario *" 
                    name="role"
                    placeholder="Seleccione un rol"
                    :options="$roles->pluck('name', 'name')->toArray()"
                    option-label="name"
                    option-value="name"
                />
                @error('role')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Botones de acción --}}
            <div class="flex gap-3 pt-4 border-t">
                <x-button 
                    type="submit" 
                    primary 
                    icon="check"
                    label="Crear Usuario"
                />
                <x-button 
                    href="{{ route('admin.users.index') }}" 
                    flat 
                    icon="arrow-left"
                    label="Cancelar"
                />
            </div>
        </form>
    </div>
@endcomponent
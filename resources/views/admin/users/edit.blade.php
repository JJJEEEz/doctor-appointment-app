@php
    $title = 'Editar Usuario';
    $breadcrumbs = [
        ['name' => 'Inicio', 'href' => route('dashboard')],
        ['name' => 'Usuarios', 'href' => route('admin.users.index')],
        ['name' => 'Editar Usuario'],
    ];
@endphp

@component('layouts.admin', ['title' => $title, 'breadcrumbs' => $breadcrumbs])
    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-2xl font-semibold mb-6 text-gray-800">Editar Usuario</h2>

        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Nombre --}}
                <div>
                    <x-input 
                        label="Nombre Completo *" 
                        name="name" 
                        placeholder="Ej: Juan Pérez"
                        value="{{ old('name', $user->name) }}"
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
                        value="{{ old('email', $user->email) }}"
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
                        value="{{ old('id_number', $user->id_number) }}"
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
                        value="{{ old('phone', $user->phone) }}"
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
                >{{ old('address', $user->address) }}</x-textarea>
                @error('address')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="border-t pt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Cambiar Contraseña (Opcional)</h3>
                <p class="text-sm text-gray-600 mb-4">Deja estos campos vacíos si no deseas cambiar la contraseña</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Nueva Contraseña --}}
                    <div>
                        <x-inputs.password 
                            label="Nueva Contraseña" 
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
                            label="Confirmar Nueva Contraseña" 
                            name="password_confirmation" 
                            placeholder="Repite la contraseña"
                            autocomplete="new-password"
                        />
                    </div>
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
                    wire:model="role"
                />
                @error('role')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                
                @php
                    $currentRole = $user->roles->first()?->name ?? '';
                @endphp
                <input type="hidden" name="role" value="{{ old('role', $currentRole) }}" x-ref="roleInput">
                
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const select = document.querySelector('select[name="role"]');
                        if (select) {
                            select.value = "{{ old('role', $currentRole) }}";
                        }
                    });
                </script>
            </div>

            {{-- Botones de acción --}}
            <div class="flex gap-3 pt-4 border-t">
                <x-button 
                    type="submit" 
                    primary 
                    icon="check"
                    label="Actualizar Usuario"
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
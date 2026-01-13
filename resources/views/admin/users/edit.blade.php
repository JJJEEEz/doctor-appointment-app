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
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre Completo *</label>
                    <input 
                        type="text"
                        name="name" 
                        placeholder="Ej: Juan Pérez"
                        value="{{ old('name', $user->name) }}"
                        pattern="^[a-záéíóúñ\s\-']+$"
                        maxlength="255"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2 text-gray-900 focus:border-blue-500 focus:ring-blue-500"
                        required
                    />
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                    <input 
                        type="email"
                        name="email" 
                        placeholder="ejemplo@correo.com"
                        value="{{ old('email', $user->email) }}"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2 text-gray-900 focus:border-blue-500 focus:ring-blue-500"
                        required
                    />
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Número de Identificación --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Número de Identificación *</label>
                    <input 
                        type="text"
                        name="id_number" 
                        placeholder="Ej: 12345678A, DNI, Pasaporte"
                        value="{{ old('id_number', $user->id_number) }}"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2 text-gray-900 focus:border-blue-500 focus:ring-blue-500"
                        required
                    />
                    @error('id_number')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Teléfono --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                    <input 
                        type="tel"
                        name="phone" 
                        placeholder="Ej: 1234567890"
                        value="{{ old('phone', $user->phone) }}"
                        pattern="^[0-9]{10}$"
                        maxlength="10"
                        inputmode="numeric"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2 text-gray-900 focus:border-blue-500 focus:ring-blue-500"
                    />
                    <p class="mt-1 text-xs text-gray-500">Exactamente 10 dígitos numéricos</p>
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Dirección --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Dirección</label>
                <textarea 
                    name="address" 
                    placeholder="Calle, número, ciudad, código postal..."
                    rows="3"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2 text-gray-900 focus:border-blue-500 focus:ring-blue-500"
                >{{ old('address', $user->address) }}</textarea>
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
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nueva Contraseña</label>
                        <input 
                            type="password"
                            name="password" 
                            placeholder="Mínimo 8 caracteres"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2 text-gray-900 focus:border-blue-500 focus:ring-blue-500"
                        />
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Confirmar Contraseña --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Confirmar Nueva Contraseña</label>
                        <input 
                            type="password"
                            name="password_confirmation" 
                            placeholder="Repite la contraseña"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2 text-gray-900 focus:border-blue-500 focus:ring-blue-500"
                        />
                    </div>
                </div>
            </div>

            {{-- Rol --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Rol del Usuario *</label>
                @php
                    $currentRole = $user->roles->first()?->name ?? '';
                @endphp
                <select 
                    name="role"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2 text-gray-900 focus:border-blue-500 focus:ring-blue-500"
                    required
                >
                    <option value="">Seleccione un rol</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}" {{ old('role', $currentRole) == $role->name ? 'selected' : '' }}>{{ $role->name }}</option>
                    @endforeach
                </select>
                @error('role')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Botones de acción --}}
            <div class="flex gap-3 pt-4 border-t">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
                    <i class="fas fa-check mr-2"></i> Actualizar Usuario
                </button>
                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold rounded-lg transition">
                    <i class="fas fa-arrow-left mr-2"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
@endcomponent

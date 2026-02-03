<div>
	<div class="overflow-x-auto mt-4">
		<table class="min-w-full bg-white border border-gray-200">
			<thead>
				<tr>
					<th class="px-4 py-2 border">ID</th>
					<th class="px-4 py-2 border">Nombre</th>
					<th class="px-4 py-2 border">Tipo de Sangre</th>
					<th class="px-4 py-2 border">Email</th>
					<th class="px-4 py-2 border">Teléfono</th>
					<th class="px-4 py-2 border">Creado</th>
					<th class="px-4 py-2 border">Acciones</th>
				</tr>
			</thead>
			<tbody>
				@forelse($patients as $patient)
					<tr>
						<td class="px-4 py-2 border">{{ $patient->id }}</td>
						<td class="px-4 py-2 border">{{ $patient->name }}</td>
						<td class="px-4 py-2 border">{{ $patient->bloodType?->name ?? 'N/A' }}</td>
						<td class="px-4 py-2 border">{{ $patient->email }}</td>
						<td class="px-4 py-2 border">{{ $patient->phone }}</td>
						<td class="px-4 py-2 border">{{ $patient->created_at }}</td>
						<td class="px-4 py-2 border">
							@include('admin.patients.actions', ['patient' => $patient])
						</td>
					</tr>
				@empty
					<tr>
						<td colspan="7" class="px-4 py-2 border text-center">No hay pacientes registrados.</td>
					</tr>
				@endforelse
			</tbody>
		</table>
		<div class="mt-2">
			{{ $patients->links() }}
		</div>
	</div>
</div>

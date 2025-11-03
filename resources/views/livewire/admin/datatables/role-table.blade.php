<div>
	<div class="overflow-x-auto mt-4">
		<table class="min-w-full bg-white border border-gray-200">
			<thead>
				<tr>
					<th class="px-4 py-2 border">ID</th>
					<th class="px-4 py-2 border">Nombre</th>
					<th class="px-4 py-2 border">Guard</th>
					<th class="px-4 py-2 border">Creado</th>
					<th class="px-4 py-2 border">Acciones</th>
				</tr>
			</thead>
			<tbody>
				@forelse($roles as $role)
					<tr>
						<td class="px-4 py-2 border">{{ $role->id }}</td>
						<td class="px-4 py-2 border">{{ $role->name }}</td>
						<td class="px-4 py-2 border">{{ $role->guard_name }}</td>
						<td class="px-4 py-2 border">{{ $role->created_at }}</td>
						<td class="px-4 py-2 border">
							@include('admin.roles.actions', ['role' => $role])
						</td>
					</tr>
				@empty
					<tr>
						<td colspan="5" class="px-4 py-2 border text-center">No hay roles registrados.</td>
					</tr>
				@endforelse
			</tbody>
		</table>
		<div class="mt-2">
			{{ $roles->links() }}
		</div>
	</div>
</div>
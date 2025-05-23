@extends('layouts.app')

@section('title', 'Usuarios')

@section('content')
<div class="container-fluid py-4">
    <div class="row">

        {{-- ENCABEZADO --}}
        <div class="col-12 mb-4">
            <div class="card-header text-center"><h2 class="mb-0">Listado de Usuarios</h2></div>
            <br>
            <div class="mb-3 d-flex justify-content-between align-items-center">
                <x-partial.bs-return />
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> Nuevo Usuario
                </a>
            </div>
        </div>

        {{-- FILTRO --}}
        <div class="container-fluid">
            <div class="card mb-4">
                <div class="card-header"><h5 class="mb-0">Filtrar Usuarios</h5></div>
                <div class="card-body">
                    <form action="{{ route('admin.users.index') }}" method="GET" class="row g-3">
                        {{-- selector --}}
                        <div class="col-md-3">
                            <label for="filter_type" class="form-label">Filtrar por:</label>
                            <select id="filter_type" name="filter_type" class="form-select" onchange="toggleFilterFields()">
                                <option value="name"     {{ request('filter_type')=='name'     ? 'selected':'' }}>Nombre</option>
                                <option value="email"    {{ request('filter_type')=='email'    ? 'selected':'' }}>Email</option>
                                <option value="role"     {{ request('filter_type')=='role'     ? 'selected':'' }}>Rol</option>
                                <option value="document" {{ request('filter_type')=='document' ? 'selected':'' }}>N° Documento</option>
                            </select>
                        </div>

                        {{-- campos dinámicos --}}
                        <div id="name_filter" class="col-md-6 filter-field">
                            <label class="form-label">Buscar por nombre:</label>
                            <input type="text" class="form-control" name="name_search" value="{{ request('name_search') }}">
                        </div>

                        <div id="email_filter" class="col-md-6 filter-field d-none">
                            <label class="form-label">Buscar por email:</label>
                            <input type="text" class="form-control" name="email_search" value="{{ request('email_search') }}">
                        </div>

                        <div id="role_filter" class="col-md-6 filter-field d-none">
                            <label class="form-label">Buscar por rol:</label>
                            <input type="text" class="form-control" name="role_search" value="{{ request('role_search') }}">
                        </div>

                        <div id="document_filter" class="col-md-6 filter-field d-none">
                            <label class="form-label">Buscar por documento:</label>
                            <input type="text" class="form-control" name="document_search" value="{{ request('document_search') }}">
                        </div>

                        {{-- botones --}}
                        <div class="col-md-3 d-flex align-items-end">
                            <button class="btn btn-primary"><i class="bi bi-search me-1"></i> Buscar</button>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary ms-2">
                                <i class="bi bi-x-circle me-1"></i> Limpiar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- TABLA --}}
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-body px-0 pt-0 pb-2">

                    {{-- mensajes flash --}}
                    @foreach (['success'=>'alert-success','error'=>'alert-danger'] as $k=>$cls)
                        @if(session($k))
                            <div class="alert {{ $cls }} alert-dismissible fade show mx-4 mt-4">
                                {{ session($k) }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                    @endforeach

                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th>NOMBRE COMPLETO</th>
                                    <th>EMAIL</th>
                                    <th>ROL</th>
                                    <th>N° DOCUMENTO</th>
                                    <th>ACTIVO</th>
                                    <th>ACCIONES</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        <td>{{ $user->name1 }} {{ $user->name2 }} {{ $user->lastname1 }} {{ $user->lastname2 }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->role->name ?? 'Sin rol' }}</td>
                                        <td>{{ $user->document_number }}</td>
                                        <td>{{ $user->is_active ? 'Sí' : 'No' }}</td>
                                        <td>
                                            <a href="{{ route('admin.users.show', $user) }}"  class="btn btn-info btn-sm"><i class="bi bi-eye"></i> Ver</a>
                                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i> Editar</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="text-center py-4">No se encontraron usuarios</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- PAGINACIÓN + CONTADOR --}}
                    <div class="d-flex flex-column align-items-center mt-4">
                        <div class="mb-1 text-muted">
                            Mostrando {{ $users->firstItem() ?? 0 }} a {{ $users->lastItem() ?? 0 }} de {{ $users->total() }} resultados
                        </div>

                        {{ $users->onEachSide(1)->links('partials.pagination') }}
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>

{{-- JS para mostrar/ocultar campos del filtro --}}
<script>
function toggleFilterFields() {
    document.querySelectorAll('.filter-field').forEach(f=>f.classList.add('d-none'));
    const type = document.getElementById('filter_type').value;
    document.getElementById(type + '_filter').classList.remove('d-none');
}
document.addEventListener('DOMContentLoaded', toggleFilterFields);
</script>
@endsection

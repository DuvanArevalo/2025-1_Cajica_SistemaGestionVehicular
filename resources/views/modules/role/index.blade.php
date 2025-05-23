@extends('layouts.app')

@section('title', 'Roles')

@section('content')
<div class="container-fluid py-4">
    <div class="row">

        {{-- ENCABEZADO --}}
        <div class="col-12 mb-4">
            <div class="card-header text-center"><h2 class="mb-0">Listado de Roles</h2></div>
            <br>
            <div class="mb-3 d-flex justify-content-between align-items-center">
                <x-partial.bs-return />
                <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> Nuevo Rol
                </a>
            </div>
        </div>

        {{-- FILTRO --}}
        <div class="container-fluid">
            <div class="card mb-4">
                <div class="card-header"><h5 class="mb-0">Filtrar Roles</h5></div>
                <div class="card-body">
                    <form action="{{ route('admin.roles.index') }}" method="GET" class="row g-3">
                        {{-- selector --}}
                        <div class="col-md-3">
                            <label for="filter_type" class="form-label">Filtrar por:</label>
                            <select id="filter_type" name="filter_type" class="form-select" onchange="toggleFilterFields()">
                                <option value="name" {{ request('filter_type') == 'name' ? 'selected' : '' }}>Nombre</option>
                                <option value="description" {{ request('filter_type') == 'description' ? 'selected' : '' }}>Descripción</option>
                            </select>
                        </div>

                        {{-- campos dinámicos --}}
                        <div id="name_filter" class="col-md-6 filter-field">
                            <label for="name_search" class="form-label">Buscar por nombre:</label>
                            <input type="text" class="form-control" id="name_search" name="name_search" value="{{ request('name_search') }}">
                        </div>

                        <div id="description_filter" class="col-md-6 filter-field d-none">
                            <label for="description_search" class="form-label">Buscar por descripción:</label>
                            <input type="text" class="form-control" id="description_search" name="description_search" value="{{ request('description_search') }}">
                        </div>

                        {{-- botones --}}
                        <div class="col-md-3 d-flex align-items-end">
                            <button class="btn btn-primary"><i class="bi bi-search me-1"></i> Buscar</button>
                            <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary ms-2">
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
                                    <th>NOMBRE</th>
                                    <th>DESCRIPCIÓN</th>
                                    <th>ACTIVO</th>
                                    <th>ACCIONES</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($roles as $role)
                                    <tr>
                                        <td>{{ $role->name }}</td>
                                        <td>{{ $role->description }}</td>
                                        <td>{{ $role->is_active ? 'Sí' : 'No' }}</td>
                                        <td>
                                            <a href="{{ route('admin.roles.show', $role->id) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i> Ver</a>
                                            <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i> Editar</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center py-4">No se encontraron roles</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- PAGINACIÓN + CONTADOR --}}
                    <div class="d-flex flex-column align-items-center mt-4">
                        <div class="mb-1 text-muted">
                            Mostrando {{ $roles->firstItem(3) ?? 0 }} a {{ $roles->lastItem() ?? 0 }} de {{ $roles->total() }} resultados
                        </div>

                        {{ $roles->onEachSide(1)->links('partials.pagination') }}
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>

{{-- Script para los campos del filtro --}}
<script>
    function toggleFilterFields() {
        document.querySelectorAll('.filter-field').forEach(f => f.classList.add('d-none'));
        const type = document.getElementById('filter_type').value;
        document.getElementById(type + '_filter').classList.remove('d-none');
    }

    document.addEventListener('DOMContentLoaded', toggleFilterFields);
</script>
@endsection

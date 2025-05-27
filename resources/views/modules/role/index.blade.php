@extends('layouts.app')

@section('title', 'Roles')

@section('content')
<div class="container-fluid py-4">
    <div class="row">

        {{-- ENCABEZADO --}}
        <div class="col-12 mb-4">
            {{-- Título "Listado de Roles" ahora alineado a la izquierda --}}
            <div class="card-header text-start"><h2 class="mb-0">Listado de Roles</h2></div>
            <br>
            <div class="mb-3 d-flex justify-content-between align-items-center">
                {{-- Asumiendo que x-partial.bs-return es un componente para volver al dashboard --}}
                <x-partial.bs-return
                    route="{{ Auth::user()->role->name }}.dashboard" {{-- Ajusta la ruta si es diferente --}}
                    text="Volver al dashboard"
                />
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
                        {{-- Selector del tipo de filtro --}}
                        <div class="col-md-3">
                            <label for="filter_type" class="form-label">Filtrar por:</label>
                            <select id="filter_type" name="filter_type" class="form-select" onchange="toggleFilterFields()">
                                <option value="name" {{ request('filter_type') == 'name' ? 'selected' : '' }}>Nombre</option>
                                <option value="description" {{ request('filter_type') == 'description' ? 'selected' : '' }}>Descripción</option>
                                <option value="is_active" {{ request('filter_type') == 'is_active' ? 'selected' : '' }}>Estado Activo</option>
                                <option value="date_range" {{ request('filter_type') == 'date_range' ? 'selected' : '' }}>Rango de Fechas</option>
                            </select>
                        </div>

                        {{-- Campos dinámicos para los filtros --}}
                        <div id="name_filter" class="col-md-6 filter-field">
                            <label for="name_search" class="form-label">Buscar por nombre:</label>
                            <input type="text" class="form-control" id="name_search" name="name_search" value="{{ request('name_search') }}">
                        </div>

                        <div id="description_filter" class="col-md-6 filter-field d-none">
                            <label for="description_search" class="form-label">Buscar por descripción:</label>
                            <input type="text" class="form-control" id="description_search" name="description_search" value="{{ request('description_search') }}">
                        </div>

                        <div id="is_active_filter" class="col-md-6 filter-field d-none">
                            <label for="active_status" class="form-label">Estado del rol:</label>
                            <select name="active_status" id="active_status" class="form-select">
                                <option value="all" {{ request('active_status') == 'all' ? 'selected' : '' }}>Todos</option>
                                <option value="1" {{ request('active_status') == '1' ? 'selected' : '' }}>Activo</option>
                                <option value="0" {{ request('active_status') == '0' ? 'selected' : '' }}>Inactivo</option>
                            </select>
                        </div>

                        <div id="date_range_filter" class="col-md-6 filter-field d-none">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="date_from" class="form-label">Fecha inicio:</label>
                                    <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="date_to" class="form-label">Fecha fin:</label>
                                    <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
                                </div>
                            </div>
                        </div>

                        {{-- Botones de acción del filtro --}}
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary"><i class="bi bi-search me-1"></i> Buscar</button>
                            <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary ms-2">
                                <i class="bi bi-x-circle me-1"></i> Limpiar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- TABLA DE ROLES --}}
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-body px-0 pt-0 pb-2">

                    {{-- Mensajes flash --}}
                    @foreach (['success'=>'alert-success','error'=>'alert-danger'] as $k=>$cls)
                        @if(session($k))
                            <div class="alert {{ $cls }} alert-dismissible fade show mx-4 mt-4" role="alert">
                                {{ session($k) }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                    @endforeach

                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    {{-- Encabezados de columna con color gris más sutil --}}
                                    <th class="text-uppercase text-muted text-xxs font-weight-bolder opacity-7">NOMBRE</th>
                                    <th class="text-uppercase text-muted text-xxs font-weight-bolder opacity-7">DESCRIPCIÓN</th>
                                    <th class="text-uppercase text-muted text-xxs font-weight-bolder opacity-7">ACTIVO</th>
                                    <th class="text-uppercase text-muted text-xxs font-weight-bolder opacity-7">FECHA CREACIÓN</th>
                                    <th class="text-uppercase text-muted text-xxs font-weight-bolder opacity-7">ACCIONES</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($roles as $role)
                                    <tr>
                                        <td>{{ $role->name }}</td>
                                        <td>{{ $role->description }}</td>
                                        <td>
                                            @if($role->is_active)
                                                <span class="badge bg-success">Sí</span>
                                            @else
                                                <span class="badge bg-danger">No</span>
                                            @endif
                                        </td>
                                        <td>{{ $role->created_at->format('d/m/Y') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.roles.show', $role->id) }}" class="btn btn-sm btn-info">
                                                    <i class="bi bi-eye"></i> Ver
                                                </a>
                                                <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn btn-sm btn-warning">
                                                    <i class="bi bi-pencil"></i> Editar
                                                </a>
                                                {{-- Botón para activar/desactivar --}}
                                                <form action="{{ route('admin.roles.toggle-active', $role->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm {{ $role->is_active ? 'btn-danger' : 'btn-success' }}">
                                                        <i class="bi {{ $role->is_active ? 'bi-toggle-off' : 'bi-toggle-on' }}"></i>
                                                        {{ $role->is_active ? 'Desactivar' : 'Activar' }}
                                                    </button>
                                                </form>

                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center py-4">No se encontraron roles</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- PAGINACIÓN + CONTADOR --}}
                    <div class="d-flex justify-content-between align-items-center mt-4 mx-4">
                        <div class="text-muted">
                            Mostrando {{ $roles->firstItem() ?? 0 }} a {{ $roles->lastItem() ?? 0 }} de {{ $roles->total() }} resultados
                        </div>
                        <div>
                            {{-- Asegúrate de que partials.pagination exista o ajusta a 'pagination::bootstrap-5' si usas la de Laravel por defecto --}}
                            {{ $roles->onEachSide(1)->links('partials.pagination') }}
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>

{{-- Script para los campos del filtro --}}
<script>
    function toggleFilterFields() {
        // Ocultar todos los campos de filtro
        document.querySelectorAll('.filter-field').forEach(field => {
            field.classList.add('d-none');
            // Opcional: limpiar los valores al ocultar para evitar enviar datos de filtros no visibles
            field.querySelectorAll('input, select').forEach(input => {
                // Solo limpiar si no tiene un valor request previo
                if (!input.hasAttribute('value') || input.value === '') {
                     input.value = '';
                }
            });
        });

        // Mostrar el campo correspondiente al filtro seleccionado
        const filterType = document.getElementById('filter_type').value;

        if (filterType === 'name') {
            document.getElementById('name_filter').classList.remove('d-none');
        } else if (filterType === 'description') {
            document.getElementById('description_filter').classList.remove('d-none');
        } else if (filterType === 'is_active') {
            document.getElementById('is_active_filter').classList.remove('d-none');
        } else if (filterType === 'date_range') {
            document.getElementById('date_range_filter').classList.remove('d-none');
        }
    }

    // Ejecutar al cargar la página para mostrar el filtro inicial
    document.addEventListener('DOMContentLoaded', toggleFilterFields);
</script>
@endsection
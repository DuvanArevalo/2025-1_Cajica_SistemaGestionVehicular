@extends('layouts.app')

@section('title', 'Estados de Alerta')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card-header text-center">
                <h2 class="mb-0">Listado de Estados de Alerta</h2>
            </div>
            <br>
            <div class="mb-3 d-flex justify-content-between align-items-center">
                <x-partial.bs-return />
                <a href="{{ route('admin.alert-statuses.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> Nuevo Estado de Alerta
                </a>
            </div>
        </div>

        <div class="container-fluid">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Filtrar Estados de Alerta</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.alert-statuses.index') }}" method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label for="filter_type" class="form-label">Filtrar por:</label>
                            <select id="filter_type" name="filter_type" class="form-select" onchange="toggleFilterFields()">
                                <option value="type" {{ request('filter_type') == 'type' ? 'selected' : '' }}>Nombre</option>
                                <option value="description" {{ request('filter_type') == 'description' ? 'selected' : '' }}>Descripción</option>
                                <option value="date_range" {{ request('filter_type') == 'date_range' ? 'selected' : '' }}>Rango de Fechas</option>
                            </select>
                        </div>
                        
                        <div id="type_filter" class="col-md-6 filter-field">
                            <label for="type_search" class="form-label">Buscar por nombre:</label>
                            <input type="text" class="form-control" id="type_search" name="type_search" value="{{ request('type_search') }}">
                        </div>
                        
                        <div id="description_filter" class="col-md-6 filter-field d-none">
                            <label for="description_search" class="form-label">Buscar por descripción:</label>
                            <input type="text" class="form-control" id="description_search" name="description_search" value="{{ request('description_search') }}">
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
                        
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search me-1"></i> Buscar
                            </button>
                            <a href="{{ route('admin.alert-statuses.index') }}" class="btn btn-secondary ms-2">
                                <i class="bi bi-x-circle me-1"></i> Limpiar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card mb-4">
                <div class="card-body px-0 pt-0 pb-2">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show mx-4 mt-4" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show mx-4 mt-4" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">NOMBRE</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">DESCRIPCIÓN</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">FECHA CREACIÓN</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ACCIONES</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($alertStatuses as $status)
                                    <tr>
                                        <td class="ps-4">
                                            <p class="text-xs font-weight-bold mb-0">{{ $status->type }}</p>
                                        </td>
                                        <td class="ps-4">
                                            <p class="text-xs font-weight-bold mb-0">{{ Str::limit($status->description, 50) }}</p>
                                        </td>
                                        <td class="ps-4">
                                            <p class="text-xs font-weight-bold mb-0">{{ $status->created_at->format('d/m/Y') }}</p>
                                        </td>
                                        <td class="ps-4">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.alert-statuses.show', $status->id) }}" class="btn btn-sm btn-info">
                                                    <i class="bi bi-eye"></i>
                                                    Ver
                                                </a>
                                                <a href="{{ route('admin.alert-statuses.edit', $status->id) }}" class="btn btn-sm btn-warning">
                                                    <i class="bi bi-pencil"></i>
                                                    Editar
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4">No se encontraron estados de alerta</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-center mt-4">
                        {{ $alertStatuses->links() }}
                    </div>
                    
                    <div class="d-flex justify-content-center mt-2">
                        <p class="text-sm text-secondary">
                            Mostrando {{ $alertStatuses->firstItem() ?? 0 }} a {{ $alertStatuses->lastItem() ?? 0 }} de {{ $alertStatuses->total() }} resultados
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function toggleFilterFields() {
        // Ocultar todos los campos de filtro
        document.querySelectorAll('.filter-field').forEach(field => {
            field.classList.add('d-none');
        });
        
        // Mostrar el campo correspondiente al filtro seleccionado
        const filterType = document.getElementById('filter_type').value;
        document.getElementById(filterType + '_filter').classList.remove('d-none');
    }
    
    // Ejecutar al cargar la página
    document.addEventListener('DOMContentLoaded', function() {
        toggleFilterFields();
    });
</script>
@endpush
@endsection
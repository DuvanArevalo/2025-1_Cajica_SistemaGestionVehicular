@extends('layouts.app')

@section('title', 'Listado de Alertas')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12 mb-4">
            <div class="col-12 mb-4">
                <div class="card-header text-center">
                    <h2 class="mb-0">Listado de Alertas</h2>
                </div>
                <br>
                <div class="mb-3 d-flex justify-content-between align-items-center">
                    <x-partial.bs-return 
                        route="{{ Auth::user()->role->name }}.dashboard"
                        text="Volver al dashboard" 
                    />
            
                    <a href="{{ route(Auth::user()->role->name . '.alerts.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i> Nueva Alerta
                    </a>
                </div>
            </div>

        <div class="container-fluid">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Filtrar Alertas</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route(Auth::user()->role->name . '.alerts.index') }}" method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label for="filter_type" class="form-label">Filtrar por:</label>
                            <select id="filter_type" name="filter_type" class="form-select" onchange="toggleFilterFields()">
                                <option value="form" {{ request('filter_type') == 'form' ? 'selected' : '' }}>Formulario</option>
                                <option value="status" {{ request('filter_type') == 'status' ? 'selected' : '' }}>Estado</option>
                                <option value="date_range" {{ request('filter_type') == 'date_range' ? 'selected' : '' }}>Rango de Fechas</option>
                            </select>
                        </div>
                        
                        <div id="form_filter" class="col-md-6 filter-field">
                            <label for="form_search" class="form-label">Buscar por ID de formulario:</label>
                            <input type="text" class="form-control" id="form_search" name="form_search" value="{{ request('form_search') }}">
                        </div>
                        
                        <div id="status_filter" class="col-md-6 filter-field d-none">
                            <label for="status_search" class="form-label">Buscar por estado:</label>
                            <input type="text" class="form-control" id="status_search" name="status_search" value="{{ request('status_search') }}">
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
                            <a href="{{ route(Auth::user()->role->name . '.alerts.index') }}" class="btn btn-secondary ms-2">
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
                    
                    <div class="table-responsive p-4">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">FORMULARIO</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">TIPO</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ESTADO</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">FECHA CREACIÓN</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ACCIONES</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($alerts as $alert)
                                    <tr>
                                        <td>
                                            Formulario #{{ $alert->preoperationalForm->id }} ({{ $alert->preoperationalForm->vehicle->plate }} - {{ $alert->preoperationalForm->vehicle->brand->name }} {{ $alert->preoperationalForm->vehicle->model->name }})
                                        </td>
                                        <td>
                                            <span class="badge 
                                                @if($alert->alert_type === 'Respuesta') bg-success 
                                                @else bg-info 
                                                @endif">
                                                {{ $alert->alert_type }}
                                            </span>
                                        </td>
                                        <td>
                                            {{ $alert->alertStatus->type }}
                                        </td>
                                        <td>
                                            {{ $alert->created_at->format('d/m/Y') }}
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route(Auth::user()->role->name . '.alerts.show', $alert->id) }}" class="btn btn-sm btn-info">
                                                    <i class="bi bi-eye"></i>
                                                    Ver
                                                </a>
                                                <a href="{{ route(Auth::user()->role->name . '.alerts.edit', $alert->id) }}" class="btn btn-sm btn-warning">
                                                    <i class="bi bi-pencil"></i>
                                                    Editar
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">No se encontraron alertas</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <div>
                                Mostrando {{ $alerts->firstItem() }} a {{ $alerts->lastItem() }} de {{ $alerts->total() }} resultados
                            </div>
                            <div>
                                {{ $alerts->links() }}
                            </div>
                        </div>
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
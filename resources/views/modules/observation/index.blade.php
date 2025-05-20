@extends('layouts.app')

@section('title', 'Listado de Observaciones')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card-header text-center">
                <h2 class="mb-0">Listado de Observaciones</h2>
            </div>
            <br>
            <div class="mb-3 d-flex justify-content-between align-items-center">
                <x-partial.bs-return 
                    route="{{ Auth::user()->role->name }}.dashboard"
                    text="Volver al dashboard" 
                />

                @if(in_array(strtolower(Auth::user()->role->name), ['admin', 'sst']))
                <a href="{{ route(strtolower(Auth::user()->role->name) . '.observations.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> Nueva Observación
                </a>
                @endif
            </div>
        </div>

        <div class="container-fluid">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Filtrar Observaciones</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route(strtolower(Auth::user()->role->name) . '.observations.index') }}" method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label for="filter_type" class="form-label">Filtrar por:</label>
                            <select id="filter_type" name="filter_type" class="form-select" onchange="toggleFilterFields()">
                                @if(strtolower(Auth::user()->role->name) != 'conductor')
                                <option value="form" {{ request('filter_type') == 'form' ? 'selected' : '' }}>Formulario</option>
                                @endif
                                <option value="text" {{ request('filter_type') == 'text' ? 'selected' : '' }}>Observación</option>
                                <option value="section" {{ request('filter_type') == 'section' ? 'selected' : '' }}>Sección</option>
                                <option value="date_range" {{ request('filter_type') == 'date_range' ? 'selected' : '' }}>Rango de fechas</option>
                            </select>
                        </div>
                        
                        @if(strtolower(Auth::user()->role->name) != 'conductor')
                        <div id="form_filter" class="col-md-6 filter-field">
                            <label for="form_search" class="form-label">Buscar formulario:</label>
                            <input type="text" class="form-control" id="form_search" name="form_search" value="{{ request('form_search') }}">
                        </div>
                        @endif
                        
                        <div id="text_filter" class="col-md-6 filter-field d-none">
                            <label for="text_search" class="form-label">Buscar observación:</label>
                            <input type="text" class="form-control" id="text_search" name="text_search" value="{{ request('text_search') }}">
                        </div>
                        
                        <div id="section_filter" class="col-md-6 filter-field d-none">
                            <label for="section_search" class="form-label">Buscar sección:</label>
                            <input type="text" class="form-control" id="section_search" name="section_search" value="{{ request('section_search') }}">
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
                            <a href="{{ route(strtolower(Auth::user()->role->name) . '.observations.index') }}" class="btn btn-secondary ms-2">
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
                    
                    @if(session('warning'))
                        <div class="alert alert-warning alert-dismissible fade show mx-4 mt-4" role="alert">
                            {{ session('warning') }}
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
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">FORMULARIO</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">SECCIÓN</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">OBSERVACIÓN</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">FECHA CREACIÓN</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ACCIONES</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($observations as $observation)
                                    <tr>
                                        <td>
                                            Formulario #{{ $observation->form->id }} - {{ $observation->form->vehicle->plate }} {{ $observation->form->vehicle->brand->name }} {{ $observation->form->vehicle->model->name }}
                                        </td>
                                        <td>
                                            {{ $observation->section->name }}
                                        </td>
                                        <td>
                                            {{ Str::limit($observation->text, 25) }}
                                        </td>
                                        <td>
                                            {{ $observation->created_at->format('d/m/Y') }}
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route(strtolower(Auth::user()->role->name) . '.observations.show', $observation->id) }}" class="btn btn-sm btn-info">
                                                    <i class="bi bi-eye"></i>
                                                    Ver
                                                </a>
                                                @if(in_array(strtolower(Auth::user()->role->name), ['admin', 'sst']))
                                                <a href="{{ route(strtolower(Auth::user()->role->name) . '.observations.edit', $observation->id) }}" class="btn btn-sm btn-warning">
                                                    <i class="bi bi-pencil"></i>
                                                    Editar
                                                </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">No se encontraron observaciones</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <div>
                                Mostrando {{ $observations->firstItem() }} a {{ $observations->lastItem() }} de {{ $observations->total() }} resultados
                            </div>
                            <div>
                                {{ $observations->links() }}
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
        
        // Mostrar el campo correspondiente al tipo de filtro seleccionado
        const filterType = document.getElementById('filter_type').value;
        document.getElementById(filterType + '_filter').classList.remove('d-none');
    }
    
    // Ejecutar al cargar la página para mostrar el campo correcto
    document.addEventListener('DOMContentLoaded', function() {
        toggleFilterFields();
    });
</script>
@endpush
@endsection
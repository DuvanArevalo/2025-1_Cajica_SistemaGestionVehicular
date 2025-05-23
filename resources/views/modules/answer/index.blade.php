@extends('layouts.app')

@section('title', 'Listado de Respuestas')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card-header text-center">
                <h2 class="mb-0">Listado de Respuestas</h2>
            </div>
            <br>
            <div class="mb-3 d-flex justify-content-between align-items-center">
                <x-partial.bs-return 
                    route="{{ Auth::user()->role->name }}.dashboard"
                    text="Volver al dashboard" 
                />

                @if(in_array(strtolower(Auth::user()->role->name), ['admin', 'sst']))
                <a href="{{ route(Auth::user()->role->name . '.answers.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> Nueva Respuesta
                </a>
                @endif
            </div>
        </div>

        <div class="container-fluid">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Filtrar Respuestas</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route(Auth::user()->role->name . '.answers.index') }}" method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label for="filter_type" class="form-label">Filtrar por:</label>
                            <select id="filter_type" name="filter_type" class="form-select" onchange="toggleFilterFields()">
                                @if(strtolower(Auth::user()->role->name) != 'conductor')
                                <option value="form" {{ request('filter_type') == 'form' ? 'selected' : '' }}>Formulario</option>
                                @endif
                                <option value="question" {{ request('filter_type') == 'question' ? 'selected' : '' }}>Pregunta</option>
                                <option value="value" {{ request('filter_type') == 'value' ? 'selected' : '' }}>Respuesta</option>
                                <option value="date_range" {{ request('filter_type') == 'date_range' ? 'selected' : '' }}>Rango de Fechas</option>
                            </select>
                        </div>
                        
                        @if(strtolower(Auth::user()->role->name) != 'conductor')
                        <div id="form_filter" class="col-md-6 filter-field">
                            <label for="form_search" class="form-label">Buscar por formulario:</label>
                            <input type="text" class="form-control" id="form_search" name="form_search" value="{{ request('form_search') }}">
                        </div>
                        @endif
                        
                        <div id="question_filter" class="col-md-6 filter-field d-none">
                            <label for="question_search" class="form-label">Buscar por pregunta:</label>
                            <input type="text" class="form-control" id="question_search" name="question_search" value="{{ request('question_search') }}">
                        </div>
                        
                        <div id="value_filter" class="col-md-6 filter-field d-none">
                            <label for="value_search" class="form-label">Buscar por respuesta:</label>
                            <select class="form-select" id="value_search" name="value_search">
                                <option value="">Seleccione una respuesta</option>
                                <option value="1" {{ request('value_search') == '1' ? 'selected' : '' }}>Sí</option>
                                <option value="0" {{ request('value_search') == '0' ? 'selected' : '' }}>No</option>
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
                        
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search me-1"></i> Buscar
                            </button>
                            <a href="{{ route(Auth::user()->role->name . '.answers.index') }}" class="btn btn-secondary ms-2">
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
                    
                    @if(session('warning'))
                        <div class="alert alert-warning alert-dismissible fade show mx-4 mt-4" role="alert">
                            {{ session('warning') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    <div class="table-responsive p-4">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">FORMULARIO</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">SECCIÓN/ES</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">PREGUNTA</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">RESPUESTA</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">FECHA CREACIÓN</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ACCIONES</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($answers as $answer)
                                    <tr>
                                        <td>Formulario # {{ $answer->form->id }} - {{ $answer->form->vehicle->plate }} {{ $answer->form->vehicle->brand->name }} {{ $answer->form->vehicle->model->name }}</td>
                                        <td>
                                            @foreach($answer->question->sections as $section)
                                                <span>{{ $section->name }}</span>
                                            @endforeach
                                        </td>
                                        <td>{{ Str::limit($answer->question->text, 50) }}</td>
                                        <td>
                                            @if($answer->value == 1)
                                                <span class="badge bg-success">Sí</span>
                                            @else
                                                <span class="badge bg-danger">No</span>
                                            @endif
                                        </td>
                                        <td>{{ $answer->created_at->format('d/m/Y') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route(Auth::user()->role->name . '.answers.show', $answer->id) }}" class="btn btn-sm btn-info">
                                                    <i class="bi bi-eye"></i>
                                                    Ver
                                                </a>
                                                @if(in_array(strtolower(Auth::user()->role->name), ['admin', 'sst']))
                                                <a href="{{ route(Auth::user()->role->name . '.answers.edit', $answer->id) }}" class="btn btn-sm btn-warning">
                                                    <i class="bi bi-pencil"></i>
                                                    Editar
                                                </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">No se encontraron respuestas</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <div>
                                Mostrando {{ $answers->firstItem() }} a {{ $answers->lastItem() }} de {{ $answers->total() }} resultados
                            </div>
                            <div>
                                {{ $answers->links() }}
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
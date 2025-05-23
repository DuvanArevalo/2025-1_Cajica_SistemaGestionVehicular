@extends('layouts.app')

@section('title', 'Crear Alerta')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <x-partial.bs-return 
            route="{{ Auth::user()->role->name }}.alerts.index" 
            class="mb-3" 
            text="Volver al listado" 
        />

        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Crear Nueva Alerta</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route(Auth::user()->role->name . '.alerts.store') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="form_id" class="form-label">Formulario</label>
                            <select class="form-select" id="form_id" name="form_id" required>
                                <option value="">Seleccione un formulario</option>
                                @foreach($forms as $form)
                                    <option value="{{ $form->id }}" 
                                        data-sections="{{ $form->vehicle->vehicleType->sections }}">
                                        Formulario #{{ $form->id }} - {{ $form->vehicle->plate }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="section_id" class="form-label">Sección</label>
                            <select class="form-select" id="section_id" name="section_id" required disabled>
                                <option value="">Seleccione una sección</option>
                            </select>
                        </div>
                        
                        <!-- Selección de tipo de alerta -->
                        <div class="mb-3">
                            <label class="form-label">¿Como desea generar la alerta?</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="alert_type" id="alert_type_question" value="question" checked>
                                <label class="form-check-label" for="alert_type_question">
                                    Generar por pregunta
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="alert_type" id="alert_type_observation" value="observation">
                                <label class="form-check-label" for="alert_type_observation">
                                    Generar por observación
                                </label>
                            </div>
                        </div>

                        <!-- Campos para Pregunta -->
                        <div id="question_fields" class="mb-3">
                            <label for="question_id" class="form-label">Pregunta</label>
                            <select class="form-select" id="question_id" name="question_id" disabled>
                                <option value="">Seleccione una pregunta</option>
                            </select>
                        </div>
                        
                        <!-- Campos para Observación -->
                        <div id="observation_fields" class="mb-3 d-none">
                            <label for="observation_text" class="form-label">Observación</label>
                            <textarea class="form-control observation-textarea" id="observation_text" name="observation_text" rows="4" data-min-chars="50"></textarea>
                            <div class="char-counter">
                                <small>Caracteres: <span class="current-chars">0</span>/<span class="min-chars">50</span></small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="alert_status_id" class="form-label">Estado de Alerta</label>
                            <select class="form-select" id="alert_status_id" name="alert_status_id" required>
                                @foreach($alertStatuses as $status)
                                    <option value="{{ $status->id }}">{{ $status->type }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i> Guardar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
    <script src="{{ asset('js/modules/alert/create.js') }}"></script>
    <script src="{{ asset('js/modules/char_count.js') }}"></script>
@endpush

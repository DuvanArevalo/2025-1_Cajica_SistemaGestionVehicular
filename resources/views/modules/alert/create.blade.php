@extends('layouts.app')

@section('title', 'Crear Alerta')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <x-partial.bs-return />
        
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route(Auth::user()->role->name . '.alerts.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="form_id" class="form-label">Formulario Preoperacional</label>
                            <select class="form-select @error('form_id') is-invalid @enderror" id="form_id" name="form_id" required>
                                <option value="">Seleccione un formulario</option>
                                @foreach($preoperationalForms as $form)
                                    <option value="{{ $form->id }}" {{ old('form_id') == $form->id ? 'selected' : '' }}>
                                        Formulario #{{ $form->id }} - Vehículo: {{ $form->vehicle->plate }}
                                    </option>
                                @endforeach
                            </select>
                            @error('form_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="driver_name" class="form-label">Conductor</label>
                                    <input type="text" class="form-control" id="driver_name" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="vehicle_plate" class="form-label">Placa del Vehículo</label>
                                    <input type="text" class="form-control" id="vehicle_plate" readonly>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="section_id" class="form-label">Sección</label>
                            <select class="form-select @error('section_id') is-invalid @enderror" id="section_id" name="section_id" required>
                                <option value="">Seleccione una sección</option>
                            </select>
                            @error('section_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="question_id" class="form-label">Pregunta</label>
                            <select class="form-select @error('question_id') is-invalid @enderror" id="question_id" name="question_id">
                                <option value="">Seleccione una pregunta</option>
                            </select>
                            @error('question_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="answer_value" class="form-label">Respuesta</label>
                            <select class="form-select @error('answer_value') is-invalid @enderror" id="answer_value" name="answer_value">
                                <option value="">Seleccione una respuesta</option>
                                <option value="1" {{ old('answer_value') == '1' ? 'selected' : '' }}>Sí</option>
                                <option value="0" {{ old('answer_value') == '0' ? 'selected' : '' }}>No</option>
                            </select>
                            @error('answer_value')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3" id="observation_container" style="display: none;">
                            <label for="observation_text" class="form-label">Observación</label>
                            <textarea class="form-control @error('observation_text') is-invalid @enderror" id="observation_text" name="observation_text" rows="3">{{ old('observation_text') }}</textarea>
                            @error('observation_text')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="alert_status_id" class="form-label">Estado de Alerta</label>
                            <select class="form-select @error('alert_status_id') is-invalid @enderror" id="alert_status_id" name="alert_status_id" required>
                                <option value="">Seleccione un estado</option>
                                @foreach($alertStatuses as $status)
                                    <option value="{{ $status->id }}" {{ old('alert_status_id') == $status->id ? 'selected' : '' }}>
                                        {{ $status->type }}
                                    </option>
                                @endforeach
                            </select>
                            @error('alert_status_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const formSelect = document.getElementById('form_id');
        const sectionSelect = document.getElementById('section_id');
        const questionSelect = document.getElementById('question_id');
        const answerSelect = document.getElementById('answer_value');
        const observationContainer = document.getElementById('observation_container');
        const driverNameInput = document.getElementById('driver_name');
        const vehiclePlateInput = document.getElementById('vehicle_plate');

        // Cargar datos del formulario al seleccionarlo
        formSelect.addEventListener('change', function() {
            const formId = this.value;
            if (formId) {
                // Limpiar selects dependientes
                sectionSelect.innerHTML = '<option value="">Seleccione una sección</option>';
                questionSelect.innerHTML = '<option value="">Seleccione una pregunta</option>';
                
                // Obtener datos del formulario
                fetch(`{{ route(Auth::user()->role->name . '.alerts.get-form-data', '') }}/${formId}`)
                    .then(response => response.json())
                    .then(data => {
                        driverNameInput.value = data.driver_name;
                        vehiclePlateInput.value = data.vehicle;
                        
                        // Cargar secciones para el tipo de vehículo
                        return fetch(`{{ route(Auth::user()->role->name . '.alerts.get-sections', '') }}/${data.vehicle_type_id}`);
                    })
                    .then(response => response.json())
                    .then(sections => {
                        sections.forEach(section => {
                            const option = document.createElement('option');
                            option.value = section.id;
                            option.textContent = section.name;
                            sectionSelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Error:', error));
            } else {
                driverNameInput.value = '';
                vehiclePlateInput.value = '';
            }
        });

        // Cargar preguntas al seleccionar sección
        sectionSelect.addEventListener('change', function() {
            const sectionId = this.value;
            if (sectionId) {
                questionSelect.innerHTML = '<option value="">Seleccione una pregunta</option>';
                
                fetch(`{{ route(Auth::user()->role->name . '.alerts.get-questions', '') }}/${sectionId}`)
                    .then(response => response.json())
                    .then(questions => {
                        questions.forEach(question => {
                            const option = document.createElement('option');
                            option.value = question.id;
                            option.textContent = question.text;
                            questionSelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Error:', error));
            }
        });

        // Mostrar/ocultar campo de observación según la respuesta
        answerSelect.addEventListener('change', function() {
            if (this.value === '0') {
                observationContainer.style.display = 'block';
                document.getElementById('observation_text').setAttribute('required', 'required');
            } else {
                observationContainer.style.display = 'none';
                document.getElementById('observation_text').removeAttribute('required');
            }
        });

        // Inicializar el estado del campo de observación
        if (answerSelect.value === '0') {
            observationContainer.style.display = 'block';
            document.getElementById('observation_text').setAttribute('required', 'required');
        }
    });
</script>
@endpush
@endsection
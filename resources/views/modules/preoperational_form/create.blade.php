@extends('layouts.app')

@section('title', 'Crear Formulario Preoperacional')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <x-partial.bs-return 
            route="{{ Auth::user()->role->name }}.preoperational-forms.index" 
            class="mb-3" 
            text="Volver al listado" 
        />

        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Crear Nuevo Formulario Preoperacional</h6>
                </div>
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

                    <form action="{{ route(Auth::user()->role->name . '.preoperational-forms.store') }}" method="POST" id="preoperationalForm">
                        @csrf
                        
                        <!-- Selección de Conductor -->
                        <div class="mb-3">
                            <label for="user_id" class="form-label">Conductor</label>
                            <select class="form-select @error('user_id') is-invalid @enderror" id="user_id" name="user_id" required {{ $userIsDriver ?? false ? 'disabled' : '' }}>
                                <option value="">Seleccione un conductor</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ (old('user_id') == $user->id || ($preselectedUser ?? null) == $user->id) ? 'selected' : '' }}>
                                        {{ $user->fullName }}
                                    </option>
                                @endforeach
                            </select>
                            @if($userIsDriver ?? false)
                                <input type="hidden" name="user_id" value="{{ $preselectedUser }}">
                            @endif
                            @error('user_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Selección de Tipo de Vehículo y Vehículo -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="vehicle_type_id" class="form-label">Tipo de Vehículo</label>
                                    <select class="form-select @error('vehicle_type_id') is-invalid @enderror" id="vehicle_type_id" name="vehicle_type_id" {{ ($userIsDriver ?? false) && ($preselectedVehicleType ?? null) ? 'disabled' : '' }}>
                                        <option value="">Seleccione un tipo de vehículo</option>
                                        @foreach($vehicleTypes as $type)
                                            <option value="{{ $type->id }}" {{ (old('vehicle_type_id') == $type->id || ($preselectedVehicleType ?? null) == $type->id) ? 'selected' : '' }}>
                                                {{ $type->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if(($userIsDriver ?? false) && ($preselectedVehicleType ?? null))
                                        <input type="hidden" name="vehicle_type_id" value="{{ $preselectedVehicleType }}">
                                    @endif
                                    @error('vehicle_type_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="vehicle_id" class="form-label">Vehículo</label>
                                    <select class="form-select @error('vehicle_id') is-invalid @enderror" id="vehicle_id" name="vehicle_id" required {{ ($userIsDriver ?? false) && ($preselectedVehicle ?? null) ? 'disabled' : '' }}>
                                        <option value="">Seleccione un vehículo</option>
                                        @foreach($vehicles as $vehicle)
                                            <option value="{{ $vehicle->id }}" 
                                                    data-type="{{ $vehicle->vehicle_type_id }}"
                                                    data-mileage="{{ $vehicle->mileage }}"
                                                    {{ (old('vehicle_id') == $vehicle->id || ($preselectedVehicle->id ?? null) == $vehicle->id) ? 'selected' : '' }}>
                                                {{ $vehicle->plate }} - {{ $vehicle->brand->name }} {{ $vehicle->model->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if(($userIsDriver ?? false) && ($preselectedVehicle ?? null))
                                        <input type="hidden" name="vehicle_id" value="{{ $preselectedVehicle->id }}">
                                    @endif
                                    @error('vehicle_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Campos de Kilometraje -->
                        <div class="mb-3">
                            <div class="row mb-2">
                                <div class="col-12">
                                    <label class="form-label">Último kilometraje registrado</label>
                                    <div id="last_mileage_display" class="form-control bg-light">Seleccione un vehículo</div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <label for="new_mileage" class="form-label">Kilometraje Actual</label>
                                    <input type="number" class="form-control @error('new_mileage') is-invalid @enderror" id="new_mileage" name="new_mileage" value="{{ old('new_mileage') }}" placeholder="El nuevo kilometraje debe ser mayor o igual que el último registrado.">
                                    @error('new_mileage')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Contenedor para las secciones -->
                        <div id="sections-container" class="mt-4">
                            <div id="sections-info" class="alert alert-info">
                                Seleccione un tipo de vehículo para cargar las secciones correspondientes.
                            </div>
                            
                            @php
                                // Obtener todas las secciones para todos los tipos de vehículo
                                $allSections = [];
                                foreach ($vehicleTypes as $type) {
                                    $sections = $type->sections;
                                    foreach ($sections as $section) {
                                        if (!isset($allSections[$section->id])) {
                                            $allSections[$section->id] = [
                                                'section' => $section,
                                                'vehicle_types' => []
                                            ];
                                        }
                                        $allSections[$section->id]['vehicle_types'][] = $type->id;
                                    }
                                }
                            @endphp
                            
                            @foreach($allSections as $sectionData)
                                @php
                                    $section = $sectionData['section'];
                                    $vehicleTypeIds = $sectionData['vehicle_types'];
                                    $questions = $section->questions;
                                @endphp
                                
                                <div class="card mb-4 section-card" data-section-id="{{ $section->id }}" data-vehicle-types="{{ json_encode($vehicleTypeIds) }}" style="display: none;">
                                    <div class="card-header bg-light">
                                        <h5>{{ $section->name }}</h5>
                                    </div>
                                    <div class="card-body">
                                        @if($questions->isNotEmpty())
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th width="70%">Pregunta</th>
                                                        <th width="30%">Respuesta</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($questions as $question)
                                                        <tr>
                                                            <td>{{ $question->text }}</td>
                                                            <td>
                                                                <div class="btn-group w-100" role="group" aria-label="Opciones de respuesta">
                                                                    <input type="radio" class="btn-check" name="answers[{{ $question->id }}]" 
                                                                           id="answer_{{ $question->id }}_1" value="1" 
                                                                           {{ old("answers.{$question->id}") === "1" ? 'checked' : '' }}
                                                                           autocomplete="off" required>
                                                                    <label class="btn btn-outline-success" for="answer_{{ $question->id }}_1">Sí</label>
                                                                    
                                                                    <input type="radio" class="btn-check" name="answers[{{ $question->id }}]" 
                                                                           id="answer_{{ $question->id }}_0" value="0" 
                                                                           {{ old("answers.{$question->id}") === "0" ? 'checked' : '' }}
                                                                           autocomplete="off" required>
                                                                    <label class="btn btn-outline-danger" for="answer_{{ $question->id }}_0">No</label>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        @else
                                            <p class="text-muted">No hay preguntas definidas para esta sección.</p>
                                        @endif
                                        
                                        <!-- Campo de observaciones para la sección -->
                                        <div class="mb-3 mt-3">
                                            <label for="observation_{{ $section->id }}" class="form-label">Observaciones (si es necesario)</label>
                                            <textarea class="form-control observation-textarea" 
                                                      id="observation_{{ $section->id }}" 
                                                      name="observations[{{ $section->id }}]" 
                                                      rows="3" 
                                                      data-min-chars="50">{{ old("observations.{$section->id}") }}</textarea>
                                            <div class="d-flex justify-content-between mt-1">
                                                <small class="form-text text-muted">Si hay alguna observación, debe tener al menos 50 caracteres.</small>
                                                <small class="char-counter">
                                                    <span class="current-chars">0</span>/
                                                    <span class="min-chars">50</span> caracteres
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
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
    <script src="{{ asset('js/modules/preoperational_form/create.js') }}"></script>
    <script src="{{ asset('js/modules/char_count.js') }}"></script>
@endpush

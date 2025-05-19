@extends('layouts.app')

@section('title', 'Crear Respuesta')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <x-partial.bs-return 
            route="{{ Auth::user()->role->name }}.answers.index" 
            class="mb-3" 
            text="Volver al listado" 
        />

        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Crear Nueva Respuesta</h6>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    <form action="{{ route(Auth::user()->role->name . '.answers.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="form_id" class="form-label">Formulario</label>
                            <select class="form-select @error('form_id') is-invalid @enderror" id="form_id" name="form_id" required>
                                <option value="">Seleccione un formulario</option>
                                @foreach($forms as $form)
                                    <option value="{{ $form->id }}" 
                                        data-sections="{{ $form->vehicle->vehicleType->sections }}"
                                        {{ old('form_id') == $form->id ? 'selected' : '' }}>
                                        Formulario #{{ $form->id }} - {{ $form->vehicle->plate }}
                                    </option>
                                @endforeach
                            </select>
                            @error('form_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="section_id" class="form-label">Sección</label>
                            <select class="form-select @error('section_id') is-invalid @enderror" id="section_id" name="section_id" required disabled>
                                <option value="">Seleccione una sección</option>
                            </select>
                            @error('section_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="question_id" class="form-label">Pregunta</label>
                            <select class="form-select @error('question_id') is-invalid @enderror" id="question_id" name="question_id" required disabled>
                                <option value="">Seleccione una pregunta</option>
                            </select>
                            @error('question_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Respuesta</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="value" id="value_1" value="1" {{ old('value') == '1' ? 'checked' : '' }}>
                                <label class="form-check-label" for="value_1">
                                    Sí
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="value" id="value_0" value="0" {{ old('value') == '0' ? 'checked' : '' }}>
                                <label class="form-check-label" for="value_0">
                                    No
                                </label>
                            </div>
                            @error('value')
                                <div class="text-danger mt-2">{{ $message }}</div>
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
@endsection

@push('scripts')
    <script src="{{ asset('js/modules/answer/create.js') }}"></script>
@endpush
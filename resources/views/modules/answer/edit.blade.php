@extends('layouts.app')

@section('title', 'Editar Respuesta')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <x-partial.bs-return />

        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Editar Respuesta</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route(Auth::user()->role->name . '.answers.update', $answer->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="form_id" class="form-label">Formulario</label>
                            <select class="form-select @error('form_id') is-invalid @enderror" id="form_id" name="form_id" required>
                                <option value="">Seleccione un formulario</option>
                                @foreach($forms as $form)
                                    <option value="{{ $form->id }}" {{ (old('form_id', $answer->form_id) == $form->id) ? 'selected' : '' }}>
                                        Formulario #{{ $form->id }} - {{ $form->created_at->format('d/m/Y') }}
                                    </option>
                                @endforeach
                            </select>
                            @error('form_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="question_id" class="form-label">Pregunta</label>
                            <select class="form-select @error('question_id') is-invalid @enderror" id="question_id" name="question_id" required>
                                <option value="">Seleccione una pregunta</option>
                                @foreach($questions as $question)
                                    <option value="{{ $question->id }}" {{ (old('question_id', $answer->question_id) == $question->id) ? 'selected' : '' }}>
                                        {{ $question->text }}
                                    </option>
                                @endforeach
                            </select>
                            @error('question_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Respuesta</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="value" id="value_1" value="1" 
                                    {{ old('value', $answer->value) === 1 || old('value', $answer->value) === '1' ? 'checked' : '' }}>
                                <label class="form-check-label" for="value_1">
                                    Sí
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="value" id="value_0" value="0" 
                                    {{ old('value', $answer->value) === 0 || old('value', $answer->value) === '0' ? 'checked' : '' }}>
                                <label class="form-check-label" for="value_0">
                                    No
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="value" id="value_null" value="" 
                                    {{ old('value', $answer->value) === null || old('value') === '' ? 'checked' : '' }}>
                                <label class="form-check-label" for="value_null">
                                    No responder
                                </label>
                            </div>
                            @error('value')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i> Actualizar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@extends('layouts.app')

@section('title', 'Editar Respuesta')

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
                    <h6>Editar Respuesta</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route(Auth::user()->role->name . '.answers.update', $answer->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label class="form-label">Formulario</label>
                            <input type="text" class="form-control" value="Formulario # {{ $answer->form->id }} - {{ $answer->form->vehicle->plate }} {{ $answer->form->vehicle->brand->name }} {{ $answer->form->vehicle->model->name }}" disabled>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Sección</label>
                            <input type="text" class="form-control" value="{{ $answer->question->sections->pluck('name')->implode(', ') }}" disabled>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Pregunta</label>
                            <textarea class="form-control" style="resize: none;" disabled>{{ $answer->question->text }}</textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Respuesta <span class="text-danger">*</span></label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="value" id="value_1" value="1" 
                                    {{ old('value', $answer->value) === 1 ? 'checked' : '' }} required>
                                <label class="form-check-label" for="value_1">
                                    Sí
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="value" id="value_0" value="0" 
                                    {{ old('value', $answer->value) === 0 ? 'checked' : '' }} required>
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
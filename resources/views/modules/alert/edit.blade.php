@extends('layouts.app')

@section('title', 'Editar Alerta')

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
                    <h6>Editar Alerta</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route(Auth::user()->role->name . '.alerts.update', $alert->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label class="form-label">Formulario</label>
                            <input type="text" class="form-control" value="Formulario #{{ $alert->form_id }} - {{ $alert->preoperationalForm->vehicle->plate }} {{ $alert->preoperationalForm->vehicle->brand->name }} {{ $alert->preoperationalForm->vehicle->model->name }}" disabled>
                        </div>
                        
                        @if($alert->answer_id)
                            <div class="mb-3">
                                <label class="form-label">Pregunta</label>
                                <input type="text" class="form-control" value="{{ $alert->answer->question->text }}" disabled>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Respuesta</label>
                                <input type="text" class="form-control" value="No" disabled>
                            </div>
                        @endif
                        
                        @if($alert->observation_id)
                            <div class="mb-3">
                                <label class="form-label">Sección</label>
                                <input type="text" class="form-control" value="{{ $alert->observation->section->name }}" disabled>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Observación</label>
                                <textarea class="form-control" style="resize: none;" disabled>{{ $alert->observation->text }}</textarea>
                            </div>
                        @endif
                        
                        <div class="mb-3">
                            <label for="alert_status_id" class="form-label">Estado de la Alerta</label>
                            <select class="form-select @error('alert_status_id') is-invalid @enderror" id="alert_status_id" name="alert_status_id" required>
                                <option value="">Seleccione un estado</option>
                                @foreach($alertStatuses as $status)
                                    <option value="{{ $status->id }}" {{ old('alert_status_id', $alert->alert_status_id) == $status->id ? 'selected' : '' }}>
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
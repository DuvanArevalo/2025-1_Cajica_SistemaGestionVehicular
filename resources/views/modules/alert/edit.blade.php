@extends('layouts.app')

@section('title', 'Editar Alerta')

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

                    <form action="{{ route(Auth::user()->role->name . '.alerts.update', $alert->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <h6 class="text-uppercase text-body text-xs font-weight-bolder">ID de Alerta:</h6>
                                    <p>{{ $alert->id }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <h6 class="text-uppercase text-body text-xs font-weight-bolder">Formulario Preoperacional:</h6>
                                    <p>ID: {{ $alert->preoperationalForm->id }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="alert_status_id" class="form-label">Estado de Alerta</label>
                            <select class="form-select @error('alert_status_id') is-invalid @enderror" id="alert_status_id" name="alert_status_id" required>
                                @foreach($alertStatuses as $status)
                                    <option value="{{ $status->id }}" {{ (old('alert_status_id', $alert->alert_status_id) == $status->id) ? 'selected' : '' }}>
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
                                <i class="bi bi-save me-1"></i> Actualizar Estado
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
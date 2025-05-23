@extends('layouts.app')

@section('title', 'Crear Observación')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <x-partial.bs-return 
            route="{{ Auth::user()->role->name }}.observations.index" 
            class="mb-3" 
            text="Volver al listado" 
        />
        
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Crear Nueva Observación</h6>
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

                    <form action="{{ route(strtolower(Auth::user()->role->name) . '.observations.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="form_id" class="form-label">Formulario Preoperacional <span class="text-danger">*</span></label>
                            <select class="form-select @error('form_id') is-invalid @enderror" id="form_id" name="form_id" required>
                                <option value="">Seleccione un formulario</option>
                                @foreach($forms as $form)
                                    <option value="{{ $form->id }}" 
                                        data-sections="{{ json_encode($form->vehicle->vehicleType->sections) }}"
                                        {{ old('form_id') == $form->id ? 'selected' : '' }}>
                                        Formulario #{{ $form->id }} - {{ $form->vehicle->plate }} {{ $form->vehicle->brand->name }} {{ $form->vehicle->model->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('form_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="section_id" class="form-label">Sección <span class="text-danger">*</span></label>
                            <select class="form-select @error('section_id') is-invalid @enderror" id="section_id" name="section_id" required disabled>
                                <option value="">Seleccione una sección</option>
                            </select>
                            @error('section_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="text" class="form-label">Observación <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('text') is-invalid @enderror observation-textarea" id="text" name="text" rows="4" required style="resize: none;" data-min-chars="50">{{ old('text') }}</textarea>
                            <div class="char-counter">
                                <small>Caracteres: <span class="current-chars">{{ strlen(old('text', '')) }}</span>/<span class="min-chars">50</span></small>
                            </div>
                            @error('text')
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
@endsection

@push('scripts')
    <script src="{{ asset('js/modules/observation/create.js') }}"></script>
    <script src="{{ asset('js/modules/char_count.js') }}"></script>
@endpush
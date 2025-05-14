@extends('layouts.app')

@section('title', 'Editar Observación')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12 mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <x-partial.bs-return />

                <div class="card-header">
                    <h2 class="mb-0">Editar Observación</h2>
                </div>
            </div>
        </div>
        
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

                    <form action="{{ route(strtolower(Auth::user()->role->name) . '.observations.update', $observation->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="form_id" class="form-label">Formulario Preoperacional</label>
                            <select class="form-select @error('form_id') is-invalid @enderror" id="form_id" name="form_id" required>
                                <option value="">Seleccione un formulario</option>
                                @foreach($forms as $form)
                                    <option value="{{ $form->id }}" {{ (old('form_id', $observation->form_id) == $form->id) ? 'selected' : '' }}>
                                        ID: {{ $form->id }} - Vehículo: {{ $form->vehicle->license_plate }}
                                    </option>
                                @endforeach
                            </select>
                            @error('form_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="section_id" class="form-label">Sección</label>
                            <select class="form-select @error('section_id') is-invalid @enderror" id="section_id" name="section_id" required>
                                <option value="">Seleccione una sección</option>
                                @foreach($sections as $section)
                                    <option value="{{ $section->id }}" {{ (old('section_id', $observation->section_id) == $section->id) ? 'selected' : '' }}>
                                        {{ $section->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('section_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="text" class="form-label">Observación</label>
                            <textarea class="form-control @error('text') is-invalid @enderror" id="text" name="text" rows="4" required>{{ old('text', $observation->text) }}</textarea>
                            @error('text')
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Cuando cambia el formulario, actualizar las secciones disponibles
    const formSelect = document.getElementById('form_id');
    const sectionSelect = document.getElementById('section_id');
    
    formSelect.addEventListener('change', function() {
        const formId = this.value;
        if (!formId) return;
        
        // Aquí deberías hacer una petición AJAX para obtener las secciones
        // correspondientes al tipo de vehículo del formulario seleccionado
        fetch(`/api/forms/${formId}/sections`)
            .then(response => response.json())
            .then(data => {
                // Limpiar el select de secciones
                sectionSelect.innerHTML = '<option value="">Seleccione una sección</option>';
                
                // Agregar las nuevas opciones
                data.forEach(section => {
                    const option = document.createElement('option');
                    option.value = section.id;
                    option.textContent = section.name;
                    sectionSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error:', error));
    });
});
</script>
@endsection
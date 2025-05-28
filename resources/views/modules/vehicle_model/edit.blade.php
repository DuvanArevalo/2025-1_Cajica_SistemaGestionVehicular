@extends('layouts.app')

@section('title', 'Editar Modelo de Vehículo')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <x-partial.bs-return
            route="{{ Auth::user()->role->name }}.vehicle-models.index"
            class="mb-3"
            text="Volver al listado" />

        <div class="col-12">
            <div class="card mb-4">
                <div class="card-body">

                    {{-- Validación de errores --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route(Auth::user()->role->name .'.vehicle-models.update', $model->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Nombre del modelo --}}
                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre del Modelo</label>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $model->name) }}"
                                required
                            >
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Selección de marca --}}
                        <div class="mb-3">
                            <label for="brand_id" class="form-label">Marca</label>
                            <select
                                id="brand_id"
                                name="brand_id"
                                class="form-select @error('brand_id') is-invalid @enderror"
                                required
                            >
                                <option value="">-- Seleccione una marca --</option>
                                @foreach($brands as $brand)
                                    <option
                                        value="{{ $brand->id }}"
                                        {{ old('brand_id', $model->brand_id) == $brand->id ? 'selected' : '' }}
                                    >
                                        {{ $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('brand_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Botón de guardar --}}
                        <div class="d-flex justify-content-end">
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

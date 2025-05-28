@extends('layouts.app')

@section('title', 'Crear Vehículo')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <x-partial.bs-return 
            route="{{ Auth::user()->role->name }}.vehicles.index" 
            class="mb-3" 
            text="Volver al listado" 
        />
        
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Crear Nuevo Vehículo</h6>
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

                    <form action="{{ route(Auth::user()->role->name . '.vehicles.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="vehicle_type_id" class="form-label">Tipo de Vehículo <span class="text-danger">*</span></label>
                                    <select class="form-select @error('vehicle_type_id') is-invalid @enderror" id="vehicle_type_id" name="vehicle_type_id" required>
                                        <option value="">Seleccione un tipo</option>
                                        @foreach($vehicleTypes as $type)
                                            <option value="{{ $type->id }}" {{ old('vehicle_type_id') == $type->id ? 'selected' : '' }}>
                                                {{ $type->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('vehicle_type_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="brand_id" class="form-label">Marca <span class="text-danger">*</span></label>
                                    <select class="form-select @error('brand_id') is-invalid @enderror" id="brand_id" name="brand_id" required>
                                        <option value="">Seleccione una marca</option>
                                        @foreach($brands as $brand)
                                            <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                                {{ $brand->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('brand_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="model_id" class="form-label">Modelo <span class="text-danger">*</span></label>
                                    <select class="form-select @error('model_id') is-invalid @enderror" id="model_id" name="model_id" required>
                                        <option value="">Seleccione un modelo</option>
                                        @foreach($models as $model)
                                            <option value="{{ $model->id }}" {{ old('model_id') == $model->id ? 'selected' : '' }}>
                                                {{ $model->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('model_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="plate" class="form-label">Placa <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('plate') is-invalid @enderror" id="plate" name="plate" value="{{ old('plate') }}" required>
                                    @error('plate')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="model_year" class="form-label">Año del Modelo <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('model_year') is-invalid @enderror" id="model_year" name="model_year" value="{{ old('model_year') }}" min="1900" max="{{ date('Y') + 1 }}" required>
                                    @error('model_year')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="color" class="form-label">Color <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('color') is-invalid @enderror" id="color" name="color" value="{{ old('color') }}" required>
                                    @error('color')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="wheel_count" class="form-label">Número de Ruedas <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('wheel_count') is-invalid @enderror" id="wheel_count" name="wheel_count" value="{{ old('wheel_count') }}" min="1" required>
                                    @error('wheel_count')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="mileage" class="form-label">Kilometraje <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('mileage') is-invalid @enderror" id="mileage" name="mileage" value="{{ old('mileage') }}" min="0" required>
                                    @error('mileage')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="soat" class="form-label">Fecha de Vencimiento SOAT <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('soat') is-invalid @enderror" id="soat" name="soat" value="{{ old('soat') }}" required>
                                    @error('soat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="mechanical_review" class="form-label">Fecha de Vencimiento Revisión Mecánica <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('mechanical_review') is-invalid @enderror" id="mechanical_review" name="mechanical_review" value="{{ old('mechanical_review') }}" required>
                                    @error('mechanical_review')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="soat_status" class="form-label">Estado SOAT <span class="text-danger">*</span></label>
                                    <select class="form-select @error('soat_status') is-invalid @enderror" id="soat_status" name="soat_status" required>
                                        <option value="1" {{ old('soat_status') == '1' ? 'selected' : '' }}>Vigente</option>
                                        <option value="0" {{ old('soat_status') == '0' ? 'selected' : '' }}>Vencido</option>
                                    </select>
                                    @error('soat_status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="mechanical_review_status" class="form-label">Estado Revisión Mecánica <span class="text-danger">*</span></label>
                                    <select class="form-select @error('mechanical_review_status') is-invalid @enderror" id="mechanical_review_status" name="mechanical_review_status" required>
                                        <option value="1" {{ old('mechanical_review_status') == '1' ? 'selected' : '' }}>Vigente</option>
                                        <option value="0" {{ old('mechanical_review_status') == '0' ? 'selected' : '' }}>Vencido</option>
                                    </select>
                                    @error('mechanical_review_status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" {{ old('is_active') ? 'checked' : 'checked' }}>
                            <label class="form-check-label" for="is_active">¿Vehículo Activo? (marque la casilla si está activo)</label>
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
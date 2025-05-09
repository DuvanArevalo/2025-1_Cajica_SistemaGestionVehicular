@extends('layouts.app')

@section('title', 'Registro')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/auth/auth.css') }}">
@endpush

@section('content')
<section class="h-100 gradient-form">
    <div class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-xl-14">
                <div class="card rounded-3 text-black">
                    <div class="row g-0">
                        {{-- Cambiado a col-lg-7 para dar más espacio al formulario --}}
                        <div class="col-lg-7">
                            <div class="card-body p-md-5 mx-md-4">
                                
                                <div class="text-center">
                                    <span>
                                        <a href="https://www.epccajica.gov.co/">
                                            <img src="{{ asset('assets/HolaEpc.png') }}" style="width: 300px;" alt="logo">
                                        </a>
                                    </span>
                                    <h2 class="mt-1 mb-5 pb-1">Sistema de Gestión Preoperacional</h2>
                                </div>

                                <form method="POST" action="{{ route('register') }}">
                                    @csrf
                                    <p>Por favor, completa tus datos para registrarte</p>

                                    {{-- Fila para Nombres --}}
                                    <div class="row mb-3">
                                        <div class="col-md-6 mb-3 mb-md-0">
                                            <div class="form-outline">
                                                <label for="name1" class="form-label">{{ __('Primer Nombre') }}</label>
                                                <input id="name1" type="text" class="form-control @error('name1') is-invalid @enderror" name="name1" value="{{ old('name1') }}" required autocomplete="given-name" autofocus placeholder="Tu primer nombre">
                                                @error('name1')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-outline">
                                                <label for="name2" class="form-label">{{ __('Segundo Nombre') }}</label>
                                                <input id="name2" type="text" class="form-control @error('name2') is-invalid @enderror" name="name2" value="{{ old('name2') }}" autocomplete="additional-name" placeholder="(Opcional)">
                                                @error('name2')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Fila para Apellidos --}}
                                    <div class="row mb-4">
                                        <div class="col-md-6 mb-3 mb-md-0">
                                            <div class="form-outline">
                                                <label for="lastname1" class="form-label">{{ __('Primer Apellido') }}</label>
                                                <input id="lastname1" type="text" class="form-control @error('lastname1') is-invalid @enderror" name="lastname1" value="{{ old('lastname1') }}" required autocomplete="family-name" placeholder="Tu primer apellido">
                                                @error('lastname1')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-outline"> {{-- Estilo de input del login --}}
                                                <label for="lastname2" class="form-label">{{ __('Segundo Apellido') }}</label>
                                                <input id="lastname2" type="text" class="form-control @error('lastname2') is-invalid @enderror" name="lastname2" value="{{ old('lastname2') }}" autocomplete="additional-name" placeholder="(Opcional)">
                                                @error('lastname2')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Tipo Documento --}}
                                    <div class="form-outline mb-4">
                                        <label for="document_type_id" class="form-label">Tipo de documento</label>
                                        <select name="document_type_id"
                                                id="document_type_id"
                                                class="form-control @error('document_type_id') is-invalid @enderror"
                                                required>
                                            <option value="">-- Seleccione uno --</option>
                                            @foreach($documentTypes as $type)
                                            <option value="{{ $type->id }}"
                                                {{ old('document_type_id') == $type->id ? 'selected' : '' }}>
                                                {{ $type->name }} ({{ $type->abbreviation }})
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('document_type_id')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    {{-- Numero de documento --}}
                                    <div class="form-outline mb-4">
                                        <label class="form-label" for="document_number">{{ __('Número de documento') }}</label>
                                        <input id="document_number" type="text" inputmode="numeric" pattern="[0-9]*" class="form-control @error('document_number') is-invalid @enderror" name="document_number" value="{{ old('document_number') }}" required autocomplete="document_number" placeholder="Número de documento">
                                        @error('document_number')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    {{-- Email --}}
                                    <div class="form-outline mb-4">
                                        <label class="form-label" for="email">{{ __('Correo electrónico') }}</label>
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Dirección de correo">
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    {{-- Password --}}
                                    <div class="form-outline mb-4">
                                        <label class="form-label" for="password">{{ __('Contraseña') }}</label>
                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Crea una contraseña">
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    {{-- Confirm Password --}}
                                    <div class="form-outline mb-4">
                                        <label class="form-label" for="password-confirm">{{ __('Confirmar Contraseña') }}</label>
                                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Confirma tu contraseña">
                                    </div>

                                    {{-- Botón de registro --}}
                                    <div class="text-center pt-1 mb-5 pb-1">
                                        <button class="btn btn-primary btn-block fa-lg gradient-custom-blue-btn mb-3" type="submit">{{ __('Registrarse') }}</button> {{-- Botón con estilo del login --}}
                                    </div>

                                    {{-- Enlace a Login --}}
                                    <div class="d-flex align-items-center justify-content-center pb-4">
                                        <p class="mb-0 me-2">¿Ya tienes una cuenta?</p>
                                        <a href="{{ route('login') }}" class="btn btn-outline-danger">Iniciar sesión</a>
                                    </div>
                                </form>

                            </div>
                        </div>

                        <div class="col-lg-5 d-flex align-items-center gradient-custom-blue">
                            <div class="text-white px-3 py-4 p-md-5 mx-md-4">
                                <h2 class="mb-4">Únete a nuestro sistema</h2>
                                <p class="large mb-0">Regístrate para empezar a gestionar tus registros preoperacionales de manera fácil y rápida. Mantén todo organizado y accesible.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<x-govco-footer />

<script>
document
  .getElementById('document_number')
  .addEventListener('input', e => {
    e.target.value = e.target.value.replace(/\D/g, '');
  });
</script>
@endsection
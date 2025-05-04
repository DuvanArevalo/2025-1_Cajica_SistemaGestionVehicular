@push('styles')
    <link rel="stylesheet" href="{{ asset('css/auth/auth.css') }}">
@endpush

@extends('layouts.app')

@section('title', 'Login | Sistema de gestión preoperacional EPC')

@section('content')
<section class="h-100 gradient-form" style="background-color: #eee;">
    <div class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-xl-10">
            <div class="card rounded-3 text-black">
            <div class="row g-0">
                <div class="col-lg-6">
                <div class="card-body p-md-5 mx-md-4">

                    <div class="text-center">
                        <span>
                            <a href="https://www.epccajica.gov.co/">
                                <img src="{{ asset('assets/HolaEpc.png') }}" style="width: 300px;" alt="logo">
                            </a>
                        </span>
                        <h2 class="mt-1 mb-5 pb-1">Sistema de Gestión Preoperacional</h2>
                    </div>

                    <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <p>Por favor, inicia sesión en tu cuenta</p>

                    <div class="form-outline mb-4">
                        <label class="form-label" for="email">{{ __('Correo electrónico') }}</label>
                        <input type="email" id="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Dirección de correo electrónico" />
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-outline mb-4">
                        <label class="form-label" for="password">{{ __('Contraseña') }}</label>
                        <input type="password" id="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Contraseña" />
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    @if (Route::has('password.request'))
                        <div class="text-end mb-3">
                            <a class="text-muted text-decoration-none" href="{{ route('password.request') }}">
                                {{ __('¿Olvidaste tu contraseña?') }}
                            </a>
                        </div>
                    @endif


                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">
                                    {{ __('Recuérdame') }}
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="text-center pt-1 mb-5 pb-1">
                        <button class="btn btn-primary btn-block fa-lg gradient-custom-blue-btn mb-3" type="submit">{{ __('Iniciar Sesión') }}</button>
                    </div>

                    <div class="d-flex align-items-center justify-content-center pb-4">
                        <p class="mb-0 me-2">¿No tienes una cuenta?</p>
                        <a href="{{ route('register') }}" class="btn btn-outline-danger">Crea una nueva</a>
                    </div>
                    </form>

                </div>
                </div>
                <div class="col-lg-6 d-flex align-items-center gradient-custom-blue">
                <div class="text-white px-3 py-4 p-md-5 mx-md-4">
                    <h4 class="mb-4">Bienvenido al Sistema</h4>
                    <p class="small mb-0">Gestiona tus registros preoperacionales de forma eficiente y segura. Accede a tus formularios, revisa historiales y mantén el control de tus operaciones.</p>
                </div>
                </div>
            </div>
            </div>
        </div>
        </div>
    </div>
</section>

<br>
<x-govco-footer />
@endsection



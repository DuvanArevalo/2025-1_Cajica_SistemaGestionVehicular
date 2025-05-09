@extends('layouts.app')

@section('title', 'Dashboard Administrador')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Dashboard Administrador') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <h2>Bienvenido al Panel de Administración</h2>
                    <p>Desde aquí podrás gestionar todos los aspectos del Sistema de Gestión Vehicular.</p>
                    
                    <!-- Aquí puedes agregar widgets, estadísticas o accesos rápidos para administradores -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
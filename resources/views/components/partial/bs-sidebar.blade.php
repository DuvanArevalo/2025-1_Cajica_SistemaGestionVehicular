@auth

<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasNavbarLabel">¿Que haremos hoy?</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
        <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
            @php
                // Determinar el prefijo de ruta según el rol del usuario
                $rolePrefix = strtolower(Auth::user()->role->name);
            @endphp

            @if(in_array($rolePrefix, ['admin', 'sst']))
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-people"></i>
                    Usuarios
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route($rolePrefix . '.users.create') }}">Crear</a></li>
                    <li><a class="dropdown-item" href="{{ route($rolePrefix . '.users.index') }}">Listar</a></li>
                    <li><a class="dropdown-item" href="{{ route($rolePrefix . '.users.edit', 1) }}">Editar</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item disabled" aria-disabled="true" href="#">Eliminar</a></li>
                </ul>
            </li>
            @endif

            @if(in_array($rolePrefix, ['admin', 'sst']))
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-shield-lock"></i>
                    Roles
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route($rolePrefix . '.roles.create') }}">Crear</a></li>
                    <li><a class="dropdown-item" href="{{ route($rolePrefix . '.roles.index') }}">Listar</a></li>
                    <li><a class="dropdown-item" href="{{ route($rolePrefix . '.roles.edit', 1) }}">Editar</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item disabled" aria-disabled="true" href="#">Eliminar</a></li>
                </ul>
            </li>
            @endif

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-truck"></i>
                    Vehículos
                </a>
                <ul class="dropdown-menu">
                    @if(in_array($rolePrefix, ['admin', 'sst']))
                    <li><a class="dropdown-item" href="{{ route($rolePrefix . '.vehicles.create') }}">Crear</a></li>
                    @endif
                    <li><a class="dropdown-item" href="{{ route($rolePrefix . '.vehicles.index') }}">Listar</a></li>
                    @if(in_array($rolePrefix, ['admin', 'sst']))
                    <li><a class="dropdown-item" href="{{ route($rolePrefix . '.vehicles.edit', 1) }}">Editar</a></li>
                    @endif
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item disabled" aria-disabled="true" href="#">Eliminar</a></li>
                </ul>
            </li>

            @if(in_array($rolePrefix, ['admin', 'sst']))
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-list-check"></i>
                    Tipos de Vehículo
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route($rolePrefix . '.vehicle-types.create') }}">Crear</a></li>
                    <li><a class="dropdown-item" href="{{ route($rolePrefix . '.vehicle-types.index') }}">Listar</a></li>
                    <li><a class="dropdown-item" href="{{ route($rolePrefix . '.vehicle-types.edit', 1) }}">Editar</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item disabled" aria-disabled="true" href="#">Eliminar</a></li>
                </ul>
            </li>

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-tag"></i>
                    Marcas
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route($rolePrefix . '.vehicle-brands.create') }}">Crear</a></li>
                    <li><a class="dropdown-item" href="{{ route($rolePrefix . '.vehicle-brands.index') }}">Listar</a></li>
                    <li><a class="dropdown-item" href="{{ route($rolePrefix . '.vehicle-brands.edit', 1) }}">Editar</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item disabled" aria-disabled="true" href="#">Eliminar</a></li>
                </ul>
            </li>

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-car-front"></i>
                    Modelos
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route($rolePrefix . '.vehicle-models.create') }}">Crear</a></li>
                    <li><a class="dropdown-item" href="{{ route($rolePrefix . '.vehicle-models.index') }}">Listar</a></li>
                    <li><a class="dropdown-item" href="{{ route($rolePrefix . '.vehicle-models.edit', 1) }}">Editar</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item disabled" aria-disabled="true" href="#">Eliminar</a></li>
                </ul>
            </li>
            @endif

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-clipboard-check"></i>
                    Forms. Preoperacionales
                </a>
                <ul class="dropdown-menu">
                    @if(in_array($rolePrefix, ['admin', 'sst', 'conductor']))
                    <li><a class="dropdown-item" href="{{ route($rolePrefix . '.preoperational-forms.create') }}">Crear</a></li>
                    @endif
                    <li><a class="dropdown-item" href="{{ route($rolePrefix . '.preoperational-forms.index') }}">Listar</a></li>
                    @if(in_array($rolePrefix, ['admin', 'sst']))
                    <li><a class="dropdown-item" href="{{ route($rolePrefix . '.preoperational-forms.edit', 1) }}">Editar</a></li>
                    @endif
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item disabled" aria-disabled="true" href="#">Eliminar</a></li>
                </ul>
            </li>

            @if(in_array($rolePrefix, ['admin', 'sst']))
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-layout-text-sidebar"></i>
                    Secciones
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route($rolePrefix . '.sections.create') }}">Crear</a></li>
                    <li><a class="dropdown-item" href="{{ route($rolePrefix . '.sections.index') }}">Listar</a></li>
                    <li><a class="dropdown-item" href="{{ route($rolePrefix . '.sections.edit', 1) }}">Editar</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item disabled" aria-disabled="true" href="#">Eliminar</a></li>
                </ul>
            </li>

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-question-circle"></i>
                    Preguntas
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route($rolePrefix . '.questions.create') }}">Crear</a></li>
                    <li><a class="dropdown-item" href="{{ route($rolePrefix . '.questions.index') }}">Listar</a></li>
                    <li><a class="dropdown-item" href="{{ route($rolePrefix . '.questions.edit', 1) }}">Editar</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item disabled" aria-disabled="true" href="#">Eliminar</a></li>
                </ul>
            </li>
            @endif

            @if($rolePrefix == 'conductor')
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-check2-square"></i>
                    Respuestas
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route($rolePrefix . '.answers.index') }}">Mis Respuestas</a></li>
                </ul>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-chat-left-text"></i>
                    Observaciones
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route($rolePrefix . '.observations.index') }}">Mis Observaciones</a></li>
                </ul>
            </li>
            @endif

            @if(in_array($rolePrefix, ['admin', 'sst']))
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-chat-left-text"></i>
                    Observaciones
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route($rolePrefix . '.observations.create') }}">Crear</a></li>
                    <li><a class="dropdown-item" href="{{ route($rolePrefix . '.observations.index') }}">Listar</a></li>
                    <li><a class="dropdown-item" href="{{ route($rolePrefix . '.observations.edit', 1) }}">Editar</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item disabled" aria-disabled="true" href="#">Eliminar</a></li>
                </ul>
            </li>

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-exclamation-triangle"></i>
                    Alertas
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route($rolePrefix . '.alerts.create') }}">Crear</a></li>
                    <li><a class="dropdown-item" href="{{ route($rolePrefix . '.alerts.index') }}">Listar</a></li>
                    <li><a class="dropdown-item" href="{{ route($rolePrefix . '.alerts.edit', 1) }}">Editar</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item disabled" aria-disabled="true" href="#">Eliminar</a></li>
                </ul>
            </li>
            
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-card-list"></i>
                    Tipos de Documento
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route($rolePrefix . '.document-types.create') }}">Crear</a></li>
                    <li><a class="dropdown-item" href="{{ route($rolePrefix . '.document-types.index') }}">Listar</a></li>
                    <li><a class="dropdown-item" href="{{ route($rolePrefix . '.document-types.edit', 1) }}">Editar</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item disabled" aria-disabled="true" href="#">Eliminar</a></li>
                </ul>
            </li>
            @endif
            
            @if($rolePrefix == 'conductor')
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-card-list"></i>
                    Tipos de Documento
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route($rolePrefix . '.document-types.index') }}">Listar</a></li>
                </ul>
<div class="offcanvas offcanvas-start d-flex flex-column" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
    <div class="offcanvas-header justify-content-center">
        <h5 class="offcanvas-title text-center" id="offcanvasNavbarLabel">¿Qué haremos hoy?</h5>
        <button type="button" class="btn-close position-absolute end-0 me-3" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>

    <div class="offcanvas-body d-flex flex-column justify-content-between align-items-center">
        <ul class="navbar-nav w-100">
            @php
                $rolePrefix = strtolower(Auth::user()->role->name);
            @endphp

            {{-- Dashboard (¡siempre primero!) --}}
            <li class="nav-item d-flex justify-content-center">
                <a class="nav-link d-flex align-items-center" href="{{ route($rolePrefix . '.dashboard') }}">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>
            </li>

            <li class="nav-item"><hr class="sidebar-divider w-100"></li>

            {{-- Roles --}}
            @if(in_array($rolePrefix, ['admin', 'sst']))
            <li class="nav-item d-flex justify-content-center">
                <a class="nav-link d-flex align-items-center" href="{{ route($rolePrefix . '.roles.index') }}">
                    <i class="bi bi-shield-lock me-2"></i> Roles
                </a>
            </li>
            @endif

            {{-- Usuarios --}}
            @if(in_array($rolePrefix, ['admin', 'sst']))
            <li class="nav-item d-flex justify-content-center">
                <a class="nav-link d-flex align-items-center" href="{{ route($rolePrefix . '.users.index') }}">
                    <i class="bi bi-people me-2"></i> Usuarios
                </a>
            </li>
            @endif

            {{-- Tipos de Documento --}}
            @if(in_array($rolePrefix, ['admin', 'sst']))
            <li class="nav-item d-flex justify-content-center">
                <a class="nav-link d-flex align-items-center" href="{{ route($rolePrefix . '.document-types.index') }}">
                    <i class="bi bi-card-list me-2"></i> Tipos de Documento
                </a>
            </li>
            @endif

            <li class="nav-item"><hr class="sidebar-divider w-100"></li>

            {{-- Vehículos --}}
            <li class="nav-item d-flex justify-content-center">
                <a class="nav-link d-flex align-items-center" href="{{ route($rolePrefix . '.vehicles.index') }}">
                    <i class="bi bi-truck me-2"></i> Vehículos
                </a>
            </li>

            {{-- Tipos de Vehículo --}}
            @if(in_array($rolePrefix, ['admin', 'sst']))
            <li class="nav-item d-flex justify-content-center">
                <a class="nav-link d-flex align-items-center" href="{{ route($rolePrefix . '.vehicle-types.index') }}">
                    <i class="bi bi-list-check me-2"></i> Tipos de Vehículo
                </a>
            </li>

            {{-- Marcas --}}
            <li class="nav-item d-flex justify-content-center">
                <a class="nav-link d-flex align-items-center" href="{{ route($rolePrefix . '.vehicle-brands.index') }}">
                    <i class="bi bi-tag me-2"></i> Marcas
                </a>
            </li>

            {{-- Modelos --}}
            <li class="nav-item d-flex justify-content-center">
                <a class="nav-link d-flex align-items-center" href="{{ route($rolePrefix . '.vehicle-models.index') }}">
                    <i class="bi bi-car-front me-2"></i> Modelos
                </a>
            </li>
            @endif

            <li class="nav-item"><hr class="sidebar-divider w-100"></li>

            {{-- Forms Preoperacionales --}}
            <li class="nav-item d-flex justify-content-center">
                <a class="nav-link d-flex align-items-center" href="{{ route($rolePrefix . '.preoperational-forms.index') }}">
                    <i class="bi bi-clipboard-check me-2"></i> Forms. Preoperacionales
                </a>
            </li>

            {{-- Secciones --}}
            @if(in_array($rolePrefix, ['admin', 'sst']))
            <li class="nav-item d-flex justify-content-center">
                <a class="nav-link d-flex align-items-center" href="{{ route($rolePrefix . '.sections.index') }}">
                    <i class="bi bi-layout-text-sidebar me-2"></i> Secciones
                </a>
            </li>

            {{-- Preguntas --}}
            <li class="nav-item d-flex justify-content-center">
                <a class="nav-link d-flex align-items-center" href="{{ route($rolePrefix . '.questions.index') }}">
                    <i class="bi bi-question-circle me-2"></i> Preguntas
                </a>
            </li>
            @endif

            {{-- Respuestas --}}
            @if(in_array($rolePrefix, ['admin', 'sst', 'conductor']))
            <li class="nav-item d-flex justify-content-center">
                <a class="nav-link d-flex align-items-center" href="{{ route($rolePrefix . '.answers.index') }}">
                    <i class="bi bi-check2-square me-2"></i> {{ $rolePrefix === 'conductor' ? 'Mis Respuestas' : 'Respuestas' }}
                </a>
            </li>
            @endif

            {{-- Observaciones --}}
            @if($rolePrefix === 'conductor')
            <li class="nav-item d-flex justify-content-center">
                <a class="nav-link d-flex align-items-center" href="{{ route($rolePrefix . '.observations.index') }}">
                    <i class="bi bi-chat-left-text me-2"></i> Mis Observaciones
                </a>
            </li>
            @elseif(in_array($rolePrefix, ['admin', 'sst']))
            <li class="nav-item d-flex justify-content-center">
                <a class="nav-link d-flex align-items-center" href="{{ route($rolePrefix . '.observations.index') }}">
                    <i class="bi bi-chat-left-text me-2"></i> Observaciones
                </a>
            </li>
            @endif

            <li class="nav-item"><hr class="sidebar-divider w-100"></li>

            {{-- Alertas --}}
            @if(in_array($rolePrefix, ['admin', 'sst']))
            <li class="nav-item d-flex justify-content-center">
                <a class="nav-link d-flex align-items-center" href="{{ route($rolePrefix . '.alerts.index') }}">
                    <i class="bi bi-exclamation-triangle me-2"></i> Alertas
                </a>
            </li>

            {{-- Estados de Alerta --}}
            <li class="nav-item d-flex justify-content-center">
                <a class="nav-link d-flex align-items-center" href="{{ route($rolePrefix . '.alert-statuses.index') }}">
                    <i class="bi bi-card-checklist me-2"></i> Estados de Alerta
                </a>
            </li>
            @endif
        </ul>


        <!-- Separador y opciones de tema/logout al final -->
        <ul class="list-unstyled mt-auto pt-3 border-top px-2"> 
            <li class="mb-2 d-flex align-items-center justify-content-center">
                <x-partial.bs-themeToggle />
            </li>
            <li class="mb-2 d-flex align-items-center justify-content-center">
                <button id="logOut" class="btn text-danger p-0 d-flex align-items-center" type="button" aria-label="Cerrar sesión" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                    <span class="sidebar-label">Cerrar sesión</span>
        <ul class="list-unstyled w-100 mt-3">
            <li class="nav-item"><hr class="sidebar-divider w-100"></li>
            <li class="mb-2 d-flex justify-content-center">
                <x-partial.bs-themeToggle />
            </li>
            <li class="mb-2 d-flex justify-content-center">
                <button id="logOut" class="btn text-danger p-0 d-flex align-items-center" type="button" aria-label="Cerrar sesión" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                    <span>Cerrar sesión</span>
                    <i class="bi bi-box-arrow-right ms-2"></i>
                </button>
            </li>
        </ul>
    </div>
</div>

{{-- Formulario de logout --}}
<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
</form>
@endauth
<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
</form>
@endauth

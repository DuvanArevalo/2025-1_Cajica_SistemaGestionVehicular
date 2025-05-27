@auth
<div class="offcanvas offcanvas-start d-flex flex-column" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
    <div class="offcanvas-header justify-content-center">
        <h5 class="offcanvas-title text-center" id="offcanvasNavbarLabel">¿Qué haremos hoy?</h5>
        <button type="button" class="btn-close position-absolute end-0 me-3" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>

    <div class="offcanvas-body d-flex flex-column justify-content-between align-items-center">
        <ul class="navbar-nav w-100">
            @php
                $rolePrefix = strtolower(Auth::user()->role->name);
                $showAdminSSTItems = in_array($rolePrefix, ['admin', 'sst']);
                $hasRolesOrUsers = $showAdminSSTItems;
            @endphp

            {{-- Dashboard (¡siempre primero!) --}}
            <li class="nav-item d-flex justify-content-center">
                <a class="nav-link d-flex align-items-center" href="{{ route($rolePrefix . '.dashboard') }}">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>
            </li>

            @if($hasRolesOrUsers)
            <li class="nav-item"><hr class="sidebar-divider w-100"></li>

            {{-- Roles --}}
            <li class="nav-item d-flex justify-content-center">
                <a class="nav-link d-flex align-items-center" href="{{ route($rolePrefix . '.roles.index') }}">
                    <i class="bi bi-shield-lock me-2"></i> Roles
                </a>
            </li>

            {{-- Usuarios --}}
            <li class="nav-item d-flex justify-content-center">
                <a class="nav-link d-flex align-items-center" href="{{ route($rolePrefix . '.users.index') }}">
                    <i class="bi bi-people me-2"></i> Usuarios
                </a>
            </li>

            {{-- Tipos de Documento --}}
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

            {{-- Tipos de Vehículo, Marcas y Modelos (solo para admin y sst) --}}
            @if($showAdminSSTItems)
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

            {{-- Secciones y Preguntas (solo para admin y sst) --}}
            @if($showAdminSSTItems)
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
            @elseif($showAdminSSTItems)
            <li class="nav-item d-flex justify-content-center">
                <a class="nav-link d-flex align-items-center" href="{{ route($rolePrefix . '.observations.index') }}">
                    <i class="bi bi-chat-left-text me-2"></i> Observaciones
                </a>
            </li>
            @endif

            @if($showAdminSSTItems)
            <li class="nav-item"><hr class="sidebar-divider w-100"></li>

            {{-- Alertas --}}
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

<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
</form>
@endauth
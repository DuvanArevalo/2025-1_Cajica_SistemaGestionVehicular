<nav class="navbar  border-bottom border-dark-subtle bg-body position-relative">
  <div class="container-fluid">
        @auth
        <div class="d-flex">
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                <i class="bi bi-house"></i>
            </button>
            <x-partial.bs-sidebar />
        </div>
        @endauth
        
        <h3 class="navbar-brand mb-0 mx-auto text-center">
            @auth
            Bienvenido de vuelta, {{ Auth::user()->full_name }}
            @else
            {{ config('app.name', 'Sistema de gestión preoperacional - EPC') }}
            @endauth
        </h3>

        @guest
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">

            {{-- Si estoy en login, muestro sólo registro --}}
            @if (Route::currentRouteNamed('login') or Route::currentRouteNamed(''))
                @if (Route::has('register'))
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('register') }}">Registro</a>
                </li>
                @endif

            {{-- Si estoy en register, muestro sólo login --}}
            @elseif (Route::currentRouteNamed('register'))
                @if (Route::has('login'))
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">Iniciar Sesión</a>
                </li>
                @endif

            {{-- En cualquier otra ruta, muestro ambas --}}
            @else
                @if (Route::has('login'))
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">Iniciar Sesión</a>
                </li>
                @endif
                @if (Route::has('register'))
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('register') }}">Registro</a>
                </li>
                @endif
            @endif

            <li class="nav-item">
                <x-partial.bs-themeToggle />
            </li>
            </ul>
        </div>
        @endguest
    </div>
</nav>
<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
  <div class="container">
    {{-- Marca / Título --}}
    @auth
      <a class="navbar-brand" href="{{ url('/') }}">
        Bienvenido de vuelta, {{ Auth::user()->full_name }}
      </a>
    @else
      <a class="navbar-brand" href="{{ url('/') }}">
        {{ config('app.name', 'Sistema de gestión preoperacional - EPC') }}
      </a>
    @endauth

    {{-- Toggler: hamburguesa para invitados --}}
    @guest
      <button class="navbar-toggler" 
              type="button"
              data-bs-toggle="collapse"
              data-bs-target="#navbarSupportedContent"
              aria-controls="navbarSupportedContent"
              aria-expanded="false"
              aria-label="{{ __('Toggle navigation') }}">
        <span class="navbar-toggler-icon"></span>
      </button>
    @endguest

    {{-- Perfil: icono para usuarios logueados --}}
    @auth
        <ul class="navbar-nav ms-auto">
            <li class="nav-item dropdown">
            <a 
                class="nav-link dropdown-toggle d-flex align-items-center" 
                href="#" 
                id="userDropdown" 
                role="button" 
                data-bs-toggle="dropdown" 
                aria-expanded="false"
            >
                <i class="bi bi-person-circle" style="font-size:1.4rem;"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                <li>
                <a 
                    class="dropdown-item d-flex align-items-center" 
                    href="{{ route('logout') }}"
                    onclick="event.preventDefault();document.getElementById('logout-form').submit();"
                >
                    <i class="bi bi-box-arrow-right me-2"></i>
                    Cerrar sesión
                </a>
                </li>
            </ul>
            </li>
        </ul>
    @endauth


    {{-- Menú colapsable sólo para invitados --}}
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ms-auto">
        @guest
          @if (Route::has('login'))
            <li class="nav-item">
              <a class="nav-link" href="{{ route('login') }}">{{ __('Iniciar Sesión') }}</a>
            </li>
          @endif
          @if (Route::has('register'))
            <li class="nav-item">
              <a class="nav-link" href="{{ route('register') }}">{{ __('Registro') }}</a>
            </li>
          @endif
        @endguest
      </ul>
    </div>
  </div>

  {{-- Formulario de logout --}}
  @auth
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
      @csrf
    </form>
  @endauth
</nav>

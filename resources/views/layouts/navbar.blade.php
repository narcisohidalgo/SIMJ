<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- Ícono de menú a la izquierda -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars custom-blue"></i></a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="{{ route('home') }}"><i class="nav-icon fas fa-home custom-blue"></i></a>
    </li>
  </ul>

  <!-- Usuario con dropdown a la derecha -->
  @if(auth()->check())
  <ul class="navbar-nav ml-auto">
    <li class="nav-item dropdown">
      @php
      $nombre = strtoupper(auth()->user()->name);
      $iniciales = substr($nombre, 0, 2);
      @endphp

      <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-expanded="false">
        <div class="d-flex align-items-center">
          <div class="rounded-circle text-white d-flex justify-content-center align-items-center mr-2" style="width: 32px; height: 32px; font-weight: bold;">
            {{ $iniciales }}
          </div>
          <span class="text-uppercase">{{ $nombre }}</span>
        </div>
      </a>
      <div class="dropdown-menu dropdown-menu-right">
        <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
          Cerrar sesión
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
          @csrf
        </form>
      </div>
    </li>
  </ul>
  @endif
</nav>
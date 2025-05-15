<aside class="main-sidebar">
  <a href="{{ route('home') }}" class="brand-link d-flex align-items-center justify-content-center" style="background-color: #003366;">
    <img src="{{ asset('images/logosidebar.png') }}" alt="SIMJ Logo" class="brand-image" style="opacity: 1; max-height: 40px;">
  </a>
 <hr style="background-color:rgb(211, 200, 200);">
  <div class="sidebar">
    <ul class="nav nav-pills nav-sidebar flex-column">
      <li class="nav-item">
        <p>SOLUCIONES INFORMÁTICAS MJ</p>
      </li>
    </ul>
  </div>
  <hr style="background-color:rgb(211, 200, 200);">


  <div class="sidebar">
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column">
        <li class="nav-item">
          <a href="{{ route('home') }}" class="nav-link text-white">
            <i class="nav-icon fas fa-home"></i>
            <p>Inicio</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('proyectos.index') }}" class="nav-link text-white">
            <i class="nav-icon fas fa-project-diagram"></i>
            <p>Control Proyectos</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('usuarios.index') }}" class="nav-link text-white">
            <i class="nav-icon fas fa-users"></i>
            <p>Usuarios</p>
          </a>
        </li>
      </ul>
    </nav>
  </div>
</aside>
<div id="mySidenav" class="sidenav">
  @guest
  <span class="nombreNavUser">Invitado</span>
  @else
  <span class="nombreNavUser">{{ Auth::user()->name }}</span>
  @endguest
  <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
  <ul class="ulStyle inicioSideBar" style="margin-bottom: 0rem;">
    @guest
    <li class="nav-item ">
      <a class="nav-link" style="padding-left: 17px;" href="{{route('index')}}">{{__('Inicio')}}</a>
    </li>
    <li class="nav-item">
      <a class="nav-link " href="{{route('login')}}">Iniciar Sesión</a>
    </li>
    @else
    <li class="nav-item ">
      <a class="nav-link" style="padding-left: 17px;" href="{{route('index')}}">{{__('Inicio')}}</a>
    </li>
    @if(Auth::user()->hasRole('admin'))
    <button class="dropdown-btn nobrB">Administracion
      <!-- <i class="fa fa-caret-down"></i> -->
    </button>
    <div class="dropdown-container oculto nobrB">
      <a class="nav-link aInterno " href="{{route('user.index')}}" >Usuarios</a>
      <a class="nav-link aInterno " href="{{route('artistas.index')}}" >Artistas</a>
      <a class="nav-link aInterno " href="{{route('albums.index')}}" >Albums</a>
      <a class="nav-link aInterno " href="{{route('generos.index')}}" >Generos</a>
    </div>
    <button class="dropdown-btn nobrB">General
      <!-- <i class="fa fa-caret-down"></i> -->
    </button>
    <div class="dropdown-container oculto nobrB">
      <a class="nav-link aInterno " href="{{route('general.index')}}" >General</a>
    </div>
    @endif
    @if(Auth::user()->hasRole('user'))
    <button class="dropdown-btn nobrB">General
      <!-- <i class="fa fa-caret-down"></i> -->
    </button>
    <div class="dropdown-container oculto nobrB">
      <a class="nav-link aInterno " href="{{route('general.index')}}" >General</a>
      <a class="nav-link aInterno " href="{{route('general.indexMiColeccion')}}" >Mi colección</a>
    </div>
    
      
    @endif
  </ul>
  @endguest
</div>
<nav class="navbar navbar-expand-lg bg-white shadow-sm">
  <div class="container">
    <span style="font-size:30px;cursor:pointer;color: var(--blue);" onclick="openNav()">&#9776;
      <a class="navbar-brand" href="{{route('index')}}">
        {{config('app.name')}}
      </a>
    </span>

    <button class="navbar-toggler navbar-light oculto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="nav nav-pills flotanteUserDerecha">
        @guest
        <li class="nav-item">
          <a class="nav-link " href="{{route('login')}}">Iniciar Sesión</a>
        </li>
        @else
        <li class="nav-item dropdown">
          <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
            {{ Auth::user()->name }} <span class="caret"></span>
          </a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                  document.getElementById('logout-form').submit();">
              {{ __('Logout') }}
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              @csrf
            </form>
          </div>
        </li>
        @endguest
      </ul>
    </div>
  </div>
</nav>
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
  @csrf
</form>
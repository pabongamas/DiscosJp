@extends('layout')
@section('title', 'Contribuciones pendientes')
@section('content')
    <input id="nameUser" type="hidden" value="{{ Auth::user()->name }}">
    <input id="idUser" type="hidden" value="{{ Auth::user()->id }}">
    <div class="container-fluid">
        <div class="container">
            <nav aria-label="breadcrumb " style="    padding-top: 20px;">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item " aria-current="page">
                        <h4 style="padding-right: 10px">Seleccione las contribuciones de artistas que desee vincular. </h4>
                    </li>

                </ol>
            </nav>
            <div class="row">
                <div class="col-12 col-lg-12 col-md-12 col-sm-12">
                    <table id="tableContribuciones" class="table table-striped table-hover">
                        <thead class="thead-dark">
                            <tr role="row">
                                <th colspan="2" class=" text-center bdright" style="width: 20%;">Acciones</th>
                                <th class=" text-center bdright" style="width: 25%;">Nombre artista</th>
                                <th class=" text-center bdright" style="width: 25%;">Imagen</th>
                                <th class=" text-center bdright" style="width: 15%;">Pais</th>
                                <th class=" text-center bdright" style="width: 15%;">Contribucion por</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($contribuciones) > 0)
                                @foreach ($contribuciones as $contribucion)
                                    <tr>
                                        <td style="width: 10%" class=" text-center">
                                            <a class="btn btn-primary" href="#"
                                                onclick="document.getElementById('añadir_{{ $contribucion->id }}').submit()">Añadir</a>
                                            <form id="añadir_{{ $contribucion->id }}" class="d-none" method="POST"
                                                action="{{ route('artistas.añadirContribucion', $contribucion->id) }}">
                                                @csrf @method('PATCH')

                                            </form>
                                            {{-- <a class="btn btn-primary"
                                                href="{{ route('artistas.añadirContribucion', $contribucion->id) }}">Añadir</a>
                                            --}}
                                        </td>
                                        <td style="width: 10%" class="w5 text-center">
                                            <a class="btn btn-danger" href="#"
                                                onclick="document.getElementById('deleteUser_{{ $contribucion->id }}').submit()">Eliminar</a>
                                            <form id="deleteUser_{{ $contribucion->id }}" class="d-none" method="POST"
                                                action="{{ route('artistas.eliminarContribucion', $contribucion->id) }}">
                                                @csrf @method('DELETE')

                                            </form>
                                        </td>

                                        <td style="width: 25%" class="text-center">
                                            <label>{{ $contribucion->name }}</label>
                                        </td>

                                        <td style="width: 25%" class=" text-center">
                                            <img class="img-fluid img-thumbnail" src="{{ $contribucion->image }}">
                                        </td>
                                        <td style="width: 15%" class=" text-center">
                                            <label>{{ $contribucion->nombrePais }}</label>
                                        </td>
                                        <td style="width: 15%" class=" text-center">
                                            <label>{{ $contribucion->usuario }}</label>
                                        </td>

                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" class="text-center">
                                        <span>No se encontraron contribuciones pendientes registradas</span>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>

                </div>
                @isset($contribuciones)
                    <div class="col-12 col-lg-12 col-md-12" style="display: flex;justify-content: center;">
                        {{ $contribuciones->links('pagination::bootstrap-4') }}
                    </div>
                @endisset
            </div>
        </div>
    </div>
    </div>
    <div id="spinner" class="spinner">
        <div class="spinner-border text-success spinnerDiscos" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
@endsection
</div>
<script type="text/javascript" src="{{ asset('Artistas/contribuirArtista.js') }}" defer></script>

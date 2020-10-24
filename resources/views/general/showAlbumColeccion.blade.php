@extends('layout')
<script src="{{ asset('Albums/miColeccion.js') }}" defer></script>
<script src="{{ asset('Artistas/artistas.js') }}" defer></script>
@section('title',$album->name)
@section('content')
<input id="nameUser" type="hidden" value="{{Auth::user()->name}}">
<input id="idUser" type="hidden" value="{{Auth::user()->id}}">
<div class="container">
    <div class="row">
        <div class="col-12 col-lg-12 col-md-12" style="overflow: auto;padding-top: 15px;">
            <h2 style="color: {{$album->color}};" class="display-5">{{$album->name}}-{{$artista->name}} <a style="float: right;" href="{{ route('general.indexMiColeccion')}}">Regresar</a></h2>
            <h2 class="display-5">{{$album->genero}} · {{$album->anio}} </h2>
            <div class="row">
                <div class="card col-12 col-lg-6 col-md-12" style="width: 22rem;margin-bottom: 15px;box-shadow:5px 5px 8px 0px {{$album->color}};border: 1px solid {{$album->color}};">
                    <img class=" card-img-top cursorPointer" src="{{$album->image}}">
                </div>
                <div class="col-12 col-lg-6 col-md-12">
                    <table id="tableCancionesXalbum" class="table tableAlbums table-hover table-responsive">
                        <thead class="thead-dark">
                            <tr role="row">
                                <th style="width: 10%;">#</th>
                                <th class=" text-left bdright" style="width: 80%;">Cancion</th>
                                <th class=" text-right bdright" style="width: 10%;">Minutos</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($canciones)>0)
                            @foreach($canciones as $cancion)
                            <tr>
                                <td class=" text-center bdright">{{$cancion->numero_cancion}}</td>
                                <td class=" text-left bdright ">{{$cancion->name}}</td>
                                <td class=" text-right bdright ">{{$cancion->minutos}}</td>
                            </tr>
                            @endforeach

                            @else
                            <tr>
                                <td colspan="3" class="text-center">
                                    <span>No se encontraron canciones registradas</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    @endif
                </div>
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
@extends('layout')
<script src="{{ asset('Albums/miColeccion.js') }}" defer></script>
<script src="{{ asset('Artistas/artistas.js') }}" defer></script>
@section('title','Mi coleccion ')
@section('content')
<input id="nameUser" type="hidden" value="{{Auth::user()->name}}">
<input id="idUser" type="hidden" value="{{Auth::user()->id}}">
<div class="container">
    <div class="row">
        <div class="col-12 col-lg-12 col-md-12" style="overflow: auto;padding-top: 15px;">
            <div class="row">
                @isset($albums)
                @foreach($albums as $album)
                <div class="col-12 col-lg-4 col-md-4">
                    <div class="card" style="width: 22rem;margin-bottom: 15px;box-shadow:5px 5px 8px 0px {{$album->color}};border: 1px solid {{$album->color}}">
                    <!-- este es para visualizar la info por medio de js y ajax -->
                     {{-- <img onclick="verInfoAlbum({{$album->id_album}},{{$album->id_artista}})" class=" card-img-top cursorPointer" src="{{$album->image}}">--}}
                     <!-- este es para visualizar la info por medio del route y el blade de laravel -->
                        <img onclick="window.location='{{ route('albums.show',['album'=>$album->id_album,'artista'=>$album->id_artista])}}'" class=" card-img-top cursorPointer" src="{{$album->image}}">
                        <div class="card-body">
                            <span class="card-text">{{$album->nombre}}</span>
                            <p class="card-text">{{$album->artista}}</p>
                            <a class=" text-secondary d-flex justify-content-between align-items-center" href="{{route('albums.show',['album'=>$album->id_album,'artista'=>$album->id_artista])}}">IR</a>
                        </div>
                    </div>
                </div>
                @endforeach
                @endisset
            </div>
        </div>
        @isset($pagination)
        <div class="col-12 col-lg-12 col-md-12" style="display: flex;justify-content: center;">
            {{ $pagination->links('pagination::bootstrap-4') }}
        </div>
        @endisset

    </div>

</div>
<div class="modal fade bd-example-modal-lg" id="modalInfoAlbum" tabindex="-1" role="dialog" aria-labelledby="modalInfoAlbum" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div id="tituloModal">
                    <h3 id="titulo"><span id="user"></span></h3>
                    <p id="subtitulo" class="text-justify"></p>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container row">
                    <div class="col-12 col-lg-6 col-md-12">
                        <div class="card" style="width: 22rem;margin-bottom: 15px;border: none !important; ">
                            <img id="imgInfoAlbum" class="card-img-top borderAlbum" src="" style=" max-height: 350px">
                        </div>
                    </div>
                    <div class="col-12 col-lg-6 col-md-12">
                        <table class="tableAlbums">
                            <thead>
                                <tr>
                                    <td>
                                        <label id="nombreAlbum" style="font-size: 25px;font-weight: bold;display:block;"></label>
                                        <label id="nombreArtista" style="font-size: 20px;font-weight: bold;display:block;"></label>
                                        <label id="generoAño" style="font-size: 15px;display:block;"></label>
                                    </td>
                                </tr>
                            </thead>
                        </table>
                        <table id="tableCancionesXalbum" class=" tableAlbums">
                            <thead>
                                <tr>
                                    <td>#</td>
                                    <td>Cancion</td>
                                    <td>Minutos</td>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
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
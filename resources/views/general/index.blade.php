@extends('layout')
<script src="{{ asset('Artistas/artistas.js') }}" defer></script>
@section('title','Discos')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div id="divOpciones" class="col-3 div4" style="padding-top: 15px;border: 1px solid rgba(0, 0, 0, 0.125);transition: all 0.3s;">
            <ul class="list-group ulHover">
                <li class="list-group-item noBr cursorPointer" id="ListaAllArtistas">Artistas</li>
                <li class="list-group-item noBr cursorPointer" id="listaAllAlbums">Álbumes</li>
                <li class="list-group-item noBr cursorPointer" id="listaAllCanciones">Canciones</li>
                <li class="list-group-item noBr cursorPointer" id="listaAllGeneros">Generos</li>
            </ul>
        </div>
       <!--  <div class="colapsoRecords cursorPointer">
            <i id="colapseDisc" style="color: var(--blue);font-size: 30px;" class="fas fa-compact-disc rotateImg"></i>
        </div> -->
        <div class="col-3 div4 oculto" id="divArtistas" style="overflow: auto;padding-top: 15px;">
            <table id="tableAllArtistas" data-route="{{Route('artistas.showArtistas')}}" class="oculto table table-striped table-hover">
                <thead>
                    <tr>
                        <th scope="col">icono</th>
                        <th scope="col">Todos los artistas</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
            <table id="tableAllGeneros" data-route="{{Route('generos.showGeneros')}}" class="oculto table table-striped table-hover">
                <thead>
                    <tr>
                        <th scope="col">Genero</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
        <div class="col-6 div4" id="divAlbums" style="overflow: auto;padding-top: 15px;">
            <div class="row" id="rowAlbums">

            </div>
        </div>
        <div class="col-6 oculto div4 " style="overflow: auto;padding-top: 15px;" id="divAlbumsxArtista">
            <div class="row " id="rowAlbumsxArtista" style="padding-right: 15px;">

            </div>
        </div>
        <div class="col-9 col-lg-9 col-md-9 div4 oculto" id="divCanciones" style="overflow: auto;padding-top: 15px;">
            <table id="tableAllCanciones" data-route="{{Route('canciones.showCanciones')}}" class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Duracion</th>
                        <th scope="col">Artista</th>
                        <th scope="col">Álbum</th>
                        <th scope="col">Genero</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
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

<div class="modal fade bd-example-modal-lg" id="modalInfoArtista" tabindex="-1" role="dialog" aria-labelledby="modalInfoArtista" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div id="tituloModal">
                    <h3 id="tituloModalInfoArtista"><span id="user"></span></h3>
                    <p id="subtituloModalInfoArtista" class="text-justify"></p>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="bodyModalArtista">
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
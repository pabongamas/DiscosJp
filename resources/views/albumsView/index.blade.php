@extends('layout')
@section('title','Administración albums')
@section('content')
@include('sweet::alert')
<div class="container-fluid">
    <div class="container " style="/*display: flex;align-items: center;*/">
        <nav aria-label="breadcrumb " style="    padding-top: 20px;">
            <ol class="breadcrumb">
                <li class="breadcrumb-item " aria-current="page">
                    <h4>Administración Albums</h4>
                </li>
                <button id="newAlbum" class="btn btn-primary">Nuevo Album</button>
            </ol>


        </nav>
        <div class="row">
            <div class="col-12 text-center">
                <table id="tableAlbums" data-route="{{Route('albums.showAdmin')}}" class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <td colspan="2">Acciones</td>
                            <th>Id</th>
                            <th>Nombre Album</th>
                            <th>Imagen</th>
                            <th>Artista</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="modal fade bd-example-modal-lg" id="modalAdminAlbums" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
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
                        <div class="container">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="nombreArtista">Nombre Album</label>
                                    <input type="text" class="form-control" id="nombreAlbum" placeholder="Nombre del album">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="selectArtista">Artista</label>
                                    <select id="selectArtista" class="form-control">
                                        <option value="0" selected>Seleccione...</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6" style="display: flex;justify-content: center;align-items: flex-start;flex-direction: column;">
                                    <label for="inputAddress">Cargar Imagen Album</label>
                                    <input type="file" class="form-control-file" id="imgAlbum" name="single-image" accept="image/png, image/gif, image/jpeg, image/jpg" />
                                    <input type="hidden" id="base64Img">
                                </div>
                                <div class="form-group col-md-6 oculto" id="divImagen">
                                    <label for="inputAddress">Imagen Album</label>
                                    <img class="img-fluid img-thumbnail" id="albumImagen" src="">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6" style="display: flex;justify-content: center;align-items: flex-start;flex-direction: column;">
                                    <label for="inputAddress">Año</label>
                                    <input type="text" class="form-control" id="anio" />
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="inputAddress">Genero</label>
                                    <select id="selectGenero" class="form-control">
                                        <option value="0" selected>Seleccione...</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button id="actualizarAlbum" type="button" class="btn btn-primary oculto">Actualizar</button>
                        <button id="registrarAlbum" type="button" class="btn btn-primary">Registrar</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade bd-example-modal-lg" id="modalInfoAlbumSongs" tabindex="-1" role="dialog" aria-labelledby="modalInfoAlbum" aria-hidden="true">
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
                            <div class="col-12 col-lg-6 col-md-6">
                                <div class="card" style="width: 22rem;margin-bottom: 15px;border: none !important; ">
                                    <img id="imgInfoAlbum" class="card-img-top borderAlbum" src="" style=" max-height: 350px">
                                </div>
                            </div>
                            <div class="col-12 col-lg-6 col-md-6">
                                <table class=" tableAlbums">
                                    <thead>
                                        <tr>
                                            <td>
                                                <label id="nombreAlbumLabel" style="font-size: 25px;font-weight: bold;display:block;"></label>
                                                <label id="nombreArtista" style="font-size: 20px;font-weight: bold;display:block;color: var(--blue);"></label>
                                                <label id="generoAño" style="font-size: 15px;display:block;"></label>
                                                <button id="agregarCancion" class="btn btn-primary">+</button>
                                            </td>
                                        </tr>
                                    </thead>
                                </table>
                                <div class="divTableScroll">
                                    <table id="tableAddCanciones" class=" tableAlbums">
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

                    </div>
                    <div class="modal-footer">
                        <button id="guardarCanciones" class="btn btn-primary">Guardar canciones</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
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
<script type="text/javascript" src="{{ asset('Albums/Administracion.js') }}" defer></script>
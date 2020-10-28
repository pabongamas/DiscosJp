@extends('layout')
@section('title','Administración artistas')
@section('content')
@include('sweet::alert')
<div class="container-fluid">
    <div class="container " style="/*display: flex;align-items: center;*/">
        <nav aria-label="breadcrumb " style="    padding-top: 20px;">
            <ol class="breadcrumb">
                <li class="breadcrumb-item " aria-current="page">
                    <h4>Administración Artistas</h4>
                </li>
                <button id="newArtista" class="btn btn-primary">Nuevo Artista</button>
            </ol>


        </nav>
        <div class="row">
            <div class="col-12 text-center">
                <table id="tableArtistas" data-route="{{Route('artistas.showAdmin')}}" class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <td colspan="2">Acciones</td>
                            <th>Id</th>
                            <th>Nombre artista</th>
                            <th>Imagen</th>
                            <th>Pais</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="modal fade bd-example-modal-lg" id="modalAdminArtista" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
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
                                    <label for="nombreArtista">Nombre Artista</label>
                                    <input type="text" class="form-control" id="nombreArtista" placeholder="Nombre de artista">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="selectPais">Pais</label>
                                    <select id="selectPais" class="form-control">
                                        <option value="0" selected>Seleccione...</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6" style="display: flex;justify-content: center;align-items: flex-start;flex-direction: column;">
                                    <label for="inputAddress">Cargar Imagen Artista</label>
                                    <input type="file" class="form-control-file" id="imgArtista" name="single-image" accept="image/png, image/gif, image/jpeg, image/jpg" />
                                    <input type="hidden" id="base64Img">
                                </div>
                                <div class="form-group col-md-6 oculto" id="divImagen">
                                    <label for="inputAddress">Imagen Artista</label>
                                    <img class="img-fluid img-thumbnail" id="artistaImagen" src="">
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button id="actualizarArtista" type="button" class="btn btn-primary oculto">Actualizar</button>
                        <button id="registrarArtista" type="button" class="btn btn-primary">Registrar</button>
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
<script type="text/javascript" src="{{ asset('Artistas/Administracion.js') }}" defer></script>
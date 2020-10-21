@extends('layout')
@section('title','Administración Generos')
@section('content')
@include('sweet::alert')
<div class="container-fluid">
    <div class="container div4" style="/*display: flex;align-items: center;*/">
        <nav aria-label="breadcrumb " style="    padding-top: 20px;">
            <ol class="breadcrumb">
                <li class="breadcrumb-item " aria-current="page">
                    <h4>Administración Generos</h4>
                </li>
                <button id="newGenero" class="btn btn-primary">Nuevo Genero</button>
            </ol>


        </nav>
        <div class="row">
            <div class="col-12 text-center">
                <table id="tableGeneros" data-route="{{Route('generos.showAdmin')}}" class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th colspan="2">Acciones</th>
                            <th>Id</th>
                            <th>Nombre genero</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="modal fade bd-example-modal-lg" id="modalAdminGenero" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
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
                                <div class="form-group col-md-12">
                                    <label for="nombreArtista">Nombre Genero</label>
                                    <input type="text" class="form-control" id="nombreGenero" placeholder="Nombre de genero">
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button id="actualizarGenero" type="button" class="btn btn-primary oculto">Actualizar</button>
                        <button id="registrarGenero" type="button" class="btn btn-primary">Registrar</button>
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
<script type="text/javascript" src="{{ asset('Genero/genero.js') }}" defer></script>
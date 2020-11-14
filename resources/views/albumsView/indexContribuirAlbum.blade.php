@extends('layout')
@section('title', 'Contribuir album')
@section('content')
    <input id="nameUser" type="hidden" value="{{ Auth::user()->name }}">
    <input id="idUser" type="hidden" value="{{ Auth::user()->id }}">
    <div class="container-fluid">
        <div class="container">
            <nav aria-label="breadcrumb " style="    padding-top: 20px;">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item " aria-current="page">
                        <h4 style="padding-right: 10px">Contribuir Album</h4>
                    </li>

                </ol>
            </nav>
            <div class="row">
                <div class="col-12 col-lg-12 col-md-12 col-sm-12">
                    <form class="bg-white shadow rounded py-3 px-4" enctype="multipart/form-data" method="POST" action="{{ route('albums.contribuirAlbumStore') }}">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="name">Nombre Album</label>
                                <input type="text" name="name" class="form-control  @error('name') is-invalid @enderror"
                                    id="name" placeholder="-- Album --"
                                    value="{{ old('name', $contribucionAlbum->name) }}">
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="pais">Artista</label>
                                <select id="artista" name="artista" class="form-control @error('artista') is-invalid @enderror">
                                    <option value="none">Seleccione Artista</option>
                                    @if (count($artista) > 0)
                                        @foreach ($artista as $value)
                                            <option value="{{ $value->id }}"  @if (old('artista') ==  $value->id) {{ 'selected' }} @endif>{{ $value->name }}</option>
                                        @endforeach
                                    @else
                                    @endif
                                </select>
                                @error('artista')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6"
                                style="display: flex;justify-content: center;align-items: flex-start;flex-direction: column;">
                                <label for="inputAddress">Cargar Imagen Artista</label>
                                <input type="file" class="form-control-file @error('single-image') is-invalid @enderror " id="imgAlbum" name="single-image"
                                    accept="image/png, image/gif, image/jpeg, image/jpg" />
                                <input type="hidden" id="base64Img">
                                @error('single-image')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group col-md-6 oculto" id="divImagen">
                                <label for="image">Imagen Album</label>
                                <img class="img-fluid img-thumbnail" name="image" id="albumImagen" src="">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="name">Año</label>
                                <input type="text" name="anio" class="form-control  @error('anio') is-invalid @enderror"
                                    id="anio" placeholder="-- Año --"
                                    value="{{ old('anio', $contribucionAlbum->anio) }}">
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="genero">Genero</label>
                                <select id="genero" name="genero" class="form-control @error('genero') is-invalid @enderror">
                                    <option value="none">Seleccione Genero</option>
                                    @if (count($genero) > 0)
                                        @foreach ($genero as $value)
                                            <option value="{{ $value->id }}"  @if (old('genero') ==  $value->id) {{ 'selected' }} @endif>{{ $value->name }}</option>
                                        @endforeach
                                    @else
                                    @endif
                                </select>
                                @error('genero')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                        </div>
                        <button type="submit" class="btn btn-primary">Contribuir</button>
                    </form>
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

@extends('layout')
@section('title', 'Contribuir Genero')
@section('content')
    <input id="nameUser" type="hidden" value="{{ Auth::user()->name }}">
    <input id="idUser" type="hidden" value="{{ Auth::user()->id }}">
    <div class="container-fluid">
        <div class="container">
            <nav aria-label="breadcrumb " style="    padding-top: 20px;">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item " aria-current="page">
                        <h4 style="padding-right: 10px">Contribuir  Genero</h4>
                    </li>

                </ol>
            </nav>
            <div class="row">
                <div class="col-12 col-lg-12 col-md-12 col-sm-12">
                    <form class="bg-white shadow rounded py-3 px-4" enctype="multipart/form-data" method="POST" action="{{ route('generos.contribuirGeneroStore') }}">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="name">Nombre Genero</label>
                                <input type="text" name="name" class="form-control  @error('name') is-invalid @enderror"
                                    id="name" placeholder="-- Album --"
                                    value="{{ old('name', $contribucionGenero->name) }}">
                                @error('name')
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


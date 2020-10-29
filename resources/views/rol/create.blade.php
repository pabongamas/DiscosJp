@extends('layout')
@section('title', 'Crear Rol')
@section('content')
    <input id="nameUser" type="hidden" value="{{ Auth::user()->name }}">
    <input id="idUser" type="hidden" value="{{ Auth::user()->id }}">
    <div class="container-fluid">
        <div class="container div4">
            <nav aria-label="breadcrumb " style="    padding-top: 20px;">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item " aria-current="page">
                        <h4 style="padding-right: 10px">Crear Rol</h4>
                    </li>
                </ol>
            </nav>
            <div class="row">
                <div class="col-12 col-lg-12 col-md-12 col-sm-12">
                    <form  class="bg-white shadow rounded py-3 px-4" method="POST" action="{{ route('rol.store')}}">
                      @include('rol._form',['btnText'=>'Crear Rol'])
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

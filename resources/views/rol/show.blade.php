@extends('layout')
@section('title', $rol->name)
@section('content')
    <input id="nameUser" type="hidden" value="{{ Auth::user()->name }}">
    <input id="idUser" type="hidden" value="{{ Auth::user()->id }}">
    <main class="py-4">
        <div class="container div4">
            <div class="bg-white p-5 shadow rounded">
                <h1 class="">{{$rol->name}}</h1>
                <p class="text-secondary">Descripción: {{$rol->description}}</p>
                <p class="text-black-50"> @if (is_null($rol->created_at)) sin información de creación de rol @else creado {{$rol->created_at->diffForHumans()}} @endif</p>
                <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('rol.index')}}">Regresar</a>
                @auth
                <div class="btn-group btn-group-sm">
                        <a class="btn btn-primary" href="{{route('rol.edit',$rol)}}">Editar</a>
                        <a class="btn btn-danger" href="#" onclick="document.getElementById('deleteRol').submit()">Eliminar</a>
                </div>
                        <form id="deleteRol" class="d-none" method="POST" action="{{route('rol.destroy',$rol)}}">
                                @csrf @method('DELETE')
        
                        </form>
                @endauth
                </div>
        
        
         </div>
        </div>
    </main>
    {{-- </div> --}}
    <div id="spinner" class="spinner">
        <div class="spinner-border text-success spinnerDiscos" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
@endsection
</div>

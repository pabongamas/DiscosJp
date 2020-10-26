@extends('layout')
@section('title', $user->fullname)
@section('content')
    <input id="nameUser" type="hidden" value="{{ Auth::user()->name }}">
    <input id="idUser" type="hidden" value="{{ Auth::user()->id }}">
    <main class="py-4">
        <div class="container div4">
            <div class="bg-white p-5 shadow rounded">
                <h1 class="">{{$user->fullname}}</h1>
                <p class="text-secondary">Usuario: {{$user->name}}</p>
                <p class="text-secondary">Email: {{$user->email}}</p>
                <p class="text-secondary">Fecha nacimiento: {{$user->birthdate}}</p>
                <p class="text-black-50">Creado {{$user->created_at->diffForHumans()}}</p>
                <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('user.index')}}">Regresar</a>
                @auth
                <div class="btn-group btn-group-sm">
                        <a class="btn btn-primary" href="{{route('user.edit',$user)}}">Editar</a>
                        <a class="btn btn-danger" href="#" onclick="document.getElementById('deleteUser').submit()">Eliminar</a>
                </div>
                        <form id="deleteUser" class="d-none" method="POST" action="{{route('user.destroy',$user)}}">
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

@extends('layout')
@section('title', 'Administración usuarios')
@section('content')
    <input id="nameUser" type="hidden" value="{{ Auth::user()->name }}">
    <input id="idUser" type="hidden" value="{{ Auth::user()->id }}">
    <div class="container-fluid">
        <div class="container div4">
            <nav aria-label="breadcrumb " style="    padding-top: 20px;">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item " aria-current="page">
                        <h4 style="padding-right: 10px;padding-top: 4px;">Administración Usuarios</h4>
                        <a class="btn btn-primary" href="{{ route('user.create') }}">Nuevo usuario</a>
                    </li>
                </ol>
            </nav>
            <div class="row">
                <div class="col-12 col-lg-12 col-md-12 col-sm-12">
                    <table id="tableUser" class="table table-striped table-hover table-responsive">
                        <thead class="thead-dark">
                            <tr role="row">
                                <th colspan="2" style="width: 10%;">Acciones</th>
                                <th class=" text-center bdright" style="width: 5%;">id</th>
                                <th class=" text-center bdright" style="width: 15%;">Nombre</th>
                                <th class=" text-center bdright" style="width: 15%;">Usuario</th>
                                <th class=" text-center bdright" style="width: 15%;">Correo</th>
                                <th class=" text-center bdright" style="width: 15%;">Fecha nacimiento</th>
                                <th class=" text-center bdright" style="width: 10%;">Genero</th>
                                <th class=" text-center bdright" style="width: 15%;">Roles</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($users) > 0)
                                @foreach ($users as $user)
                                    <tr>
                                        <td class="w5 text-center">
                                            {{-- <i class="fas fa-user-times"></i>
                                            --}}
                                            <a class="btn btn-primary" href="{{ route('user.edit', $user->id) }}">Editar</a>
                                        </td>
                                        <td class="w5 text-center">
                                            {{-- <i class="fas fa-pencil-alt"></i>
                                            --}}
                                            <a class="btn btn-danger" href="#"
                                                onclick="document.getElementById('deleteUser_{{ $user->id }}').submit()">Eliminar</a>
                                            <form id="deleteUser_{{ $user->id }}" class="d-none" method="POST"
                                                action="{{ route('user.destroy', $user->id) }}">
                                                @csrf @method('DELETE')

                                            </form>
                                        </td>
                                        <td class="w5 text-center">
                                            <label>{{ $user->id }}</label>
                                        </td>
                                        <td class="w15 text-center">
                                            {{-- <label>{{ $user->fullname }}</label>
                                            --}}
                                            <a class="d-inline-block" tabindex="0" data-toggle="tooltip"
                                                title="Disabled tooltip" class=""
                                                href="{{ route('user.show', $user->id) }}">{{ $user->fullname }}</a>
                                        </td>
                                        <td class="w15 text-center">
                                            <label>{{ $user->name }}</label>
                                        </td>
                                        <td class="w15 text-center">
                                            <label>{{ $user->email }}</label>
                                        </td>
                                        <td class="w15 text-center">
                                            <label>{{ $user->birthdate }}</label>
                                        </td>
                                        <td class="w10 text-center">
                                            @if ($user->gender === 1)
                                                <label>Masculino</label>
                                            @elseif($user->gender===0)
                                                <label>Femenino</label>
                                            @endif
                                        </td>
                                        <td class="w15 text-center">
                                            <label>@if (is_null($user->rolDescription)) Sin rol @else {{$user->rolDescription}} @endif</label>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="9" class="text-center">
                                        <span>No se encontraron usuarios registrados</span>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>

                </div>
                @isset($users)
                    <div class="col-12 col-lg-12 col-md-12" style="display: flex;justify-content: center;">
                        {{ $users->links('pagination::bootstrap-4') }}
                    </div>
                @endisset

            </div>
        </div>
    </div>
    <div class="modal fade bd-example-modal-lg" id="modalInfoAlbum" tabindex="-1" role="dialog"
        aria-labelledby="modalInfoAlbum" aria-hidden="true">
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

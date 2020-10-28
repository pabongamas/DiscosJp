@extends('layout')
@section('title', 'Administración Roles')
@section('content')
    <input id="nameUser" type="hidden" value="{{ Auth::user()->name }}">
    <input id="idUser" type="hidden" value="{{ Auth::user()->id }}">
    <div class="container-fluid">
        <div class="container">
            <nav aria-label="breadcrumb " style="    padding-top: 20px;">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item " aria-current="page">
                        <h4 style="padding-right: 10px;padding-top: 4px;">Administración Usuarios</h4>
                        <a class="btn btn-primary" href="{{ route('rol.create') }}">Nuevo Rol</a>
                    </li>
                </ol>
            </nav>
            <div class="row">
                <div class="col-12 col-lg-12 col-md-12 col-sm-12">
                    <table id="tableRol" class="table table-striped table-hover table-responsive">
                        <thead class="thead-dark">
                            <tr role="row">
                                <th colspan="2" style="width: 10%;">Acciones</th>
                                <th class=" text-center bdright" style="width: 10%;">id</th>
                                <th class=" text-center bdright" style="width: 40%;">Nombre</th>
                                <th class=" text-center bdright" style="width: 40%;">description</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($roles) > 0)
                                @foreach ($roles as $rol)
                                    <tr>
                                        <td class="w5 text-center">
                                            {{-- <i class="fas fa-user-times"></i>
                                            --}}
                                            <a class="btn btn-primary" href="{{ route('rol.edit', $rol->id) }}">Editar</a>
                                        </td>
                                        <td class="w5 text-center">
                                            {{-- <i class="fas fa-pencil-alt"></i>
                                            --}}
                                            <a class="btn btn-danger" href="#"
                                                onclick="document.getElementById('deleteRol_{{ $rol->id }}').submit()">Eliminar</a>
                                            <form id="deleteRol_{{ $rol->id }}" class="d-none" method="POST"
                                                action="{{ route('rol.destroy', $rol->id) }}">
                                                @csrf @method('DELETE')

                                            </form>
                                        </td>
                                        <td class="w5 text-center">
                                            <label>{{ $rol->id }}</label>
                                        </td>
                                        <td class="w15 text-center">
                                            <a class="d-inline-block" tabindex="0" data-toggle="tooltip"
                                                title="Disabled tooltip" class=""
                                                href="{{ route('rol.show', $rol->id) }}">{{ $rol->name }}</a>
                                        </td>
                                        <td class="w15 text-center">
                                            <label>{{ $rol->description }}</label>
                                        </td>
                                      
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="9" class="text-center">
                                        <span>No se encontraron roles registrados</span>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>

                </div>
                @isset($users)
                    <div class="col-12 col-lg-12 col-md-12" style="display: flex;justify-content: center;">
                        {{ $roles->links('pagination::bootstrap-4') }}
                    </div>
                @endisset

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

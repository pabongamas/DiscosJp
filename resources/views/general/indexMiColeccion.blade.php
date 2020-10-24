@extends('layout')
<script src="{{ asset('Albums/miColeccion.js') }}" defer></script>
@section('title','Mi coleccion ')
@section('content')
<input id="nameUser" type="hidden" value="{{Auth::user()->name}}">
<input id="idUser" type="hidden" value="{{Auth::user()->id}}">
<div class="container">
    <div class="row">
        <div class="col-12 col-lg-12 col-md-12">
        <div class="row">
            @isset($albums)
            @foreach($albums as $album)
            <div class="col-12 col-lg-4 col-md-4">
                <div class="card" style="width: 18rem;margin-bottom: 15px;">
                    <img class=" card-img-top cursorPointer" src="{{$album->image}}">
                    <div class="card-body">
                        <span class="card-text">{{$album->name}}</span>
                        <p class="card-text">Ancient Rites</p>
                    </div>
                </div>
            </div>


            @endforeach
            {{ $albums->links('pagination::bootstrap-4') }}
            @endisset
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
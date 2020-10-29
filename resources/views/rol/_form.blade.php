@csrf
<div class="form-row">
    <div class="form-group col-md-6">
        <label for="name">Rol</label>
        <input type="text" name="name" class="form-control  @error('name') is-invalid @enderror" id="name"
            placeholder="-- Rol --" value="{{old('name',$rol->name)}}">
        @error('name')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
    <div class="form-group col-md-6">
        <label for="description">Descripción</label>
        <input type="text" name="description" class="form-control @error('description') is-invalid @enderror" id="description"
            placeholder="-- Descripción rol --"  value="{{old('description',$rol->description)}}" >
        @error('description')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
</div>
<button type="submit" class="btn btn-primary">{{$btnText}}</button>

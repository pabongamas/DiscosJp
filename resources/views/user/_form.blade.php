@csrf
<div class="form-row">
    <div class="form-group col-md-6">
        <label for="name">Usuario</label>
        <input type="text" name="name" class="form-control  @error('name') is-invalid @enderror" id="name"
            placeholder="-- Usuario --" value="{{old('name',$user->name)}}">
        @error('name')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
    <div class="form-group col-md-6">
        <label for="fullname">Nombre completo</label>
        <input type="text" name="fullname" class="form-control @error('fullname') is-invalid @enderror" id="fullname"
            placeholder="-- Nombre completo --"  value="{{old('fullname',$user->fullname)}}" >
        @error('fullname')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
</div>
<div class="form-row">
    <div class="form-group col-md-6">
        <label for="email">Email</label>
        <input type="email" name="email" class="form-control  @error('email') is-invalid @enderror" id="email"
        placeholder="-- Email --" value="{{old('email',$user->email)}}">
        @error('email')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
    <div class="form-group col-md-6">
       {{--  @dump($user->id) --}}
        <label for="password">Contraseña</label>
        <input type="password" name="password" class="form-control  @error('password') is-invalid @enderror"
            id="password" placeholder="-- Contraseña --" value="{{old('password',$user->password)}}">
        @error('password')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
</div>

<div class="form-row">
    <div class="form-group col-md-6">
        <label for="birthdate">Fecha de nacimiento</label>
        <input type="text" name="birthdate" class="form-control  @error('birthdate') is-invalid @enderror"
            id="birthdate" placeholder="-- Fecha nacimiento --" value="{{old('birthdate',$user->birthdate)}}">
        @error('birthdate')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
    <div class="form-group col-md-6">
        <label for="gender">Genero</label>
        <select id="gender" name="gender" class="form-control  @error('gender') is-invalid @enderror">
            <option value="none" @if(is_null($user->id)) {{'selected'}}  @else @endif>Seleccione genero</option>
            <option value="1"  {{-- @if (old('gender') == 1) {{ 'selected' }} @endif --}} @if(is_null($user->id)) @if (old('gender') == 1) {{ 'selected' }} @endif  @else @if($user->gender==1) {{'selected'}} @endif @endif>Masculino</option>
            <option value="0" {{-- @if (old('gender') == 0) {{ 'selected' }} @endif --}} @if(is_null($user->id)) @if (old('gender') == 0) {{ 'selected' }} @endif  @else @if($user->gender==0) {{'selected'}} @endif @endif >Femenino</option>
        </select>
        @error('gender')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
</div>
<div class="form-row">
    <div class="form-group col-md-6">
        <label for="rol">Rol</label>
        <select id="rol" name="rol" class="form-control @error('rol') is-invalid @enderror">
            <option value="none">Seleccione Rol</option>
            <option value="0"  @if (old('rol') == "0") {{ 'selected' }} @endif>Sin rol</option>
            @if (count($roles) > 0)
                @foreach ($roles as $roles)
                    {{-- <option value="{{ $roles->id }}" @if (old('rol') ==  $roles->id) {{ 'selected' }} @endif >{{ $roles->description }}</option> --}}
                    <option value="{{ $roles->id }}" @if(empty($userRol[0]))  @if (old('rol') ==  $roles->id) {{ 'selected' }} @endif @else @if($userRol[0]->role_id== $roles->id) {{'selected'}} @endif  @endif >{{ $roles->description }}</option>
                @endforeach
            @else
            @endif

        </select>
        @error('rol')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
</div>
<button type="submit" class="btn btn-primary">{{$btnText}}</button>

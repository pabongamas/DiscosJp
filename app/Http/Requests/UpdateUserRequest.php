<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'=>'required|string|unique:users,name,'.$this->user->id,
            'fullname'=>'required|string',
            'email' => 'required|email|unique:users,email,'.$this->user->id,
            'password' => 'required|string',
            'birthdate' => 'required|date',
            'gender' => 'required|numeric|not_in:none',
            'rol'=>'required|numeric|not_in:none',
        ];
    }
    public function messages(){
        return [
            'name.unique'=>'Este nombre de usuario ya esta registrado',
            'name.required'=>'El usuario es requerido',
            'fullname.required'=>'El nombre del usuario es requerido',
            'email.required'=>'El email es requerido',
            'email.unique'=>'Este email ya esta registrado',
            'password.required'=>'La contraseña es requerida',
            'birthdate.required'=>'la fecha de nacimiento es requerida',
            'gender.numeric'=>'No selecciono el genero del usuario',
            'rol.numeric'=>'No selecciono el rol del usuario',
        ];
    }
}

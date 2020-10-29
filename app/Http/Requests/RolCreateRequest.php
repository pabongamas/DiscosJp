<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RolCreateRequest extends FormRequest
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
            'name'=>'required|string|unique:roles',
            'description'=>'required|string',
        ];
    }

    public function messages(){
        return [
            'name.unique'=>'Este nombre de rol ya esta registrado',
            'name.required'=>'El Rol es requerido',
            'description.required'=>'la descripción del rol es requerida',
        ];
    }
}

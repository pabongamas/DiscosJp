<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRolRequest extends FormRequest
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
            'name'=>'required|string|unique:roles,name,'.$this->rol->id,
            'description'=>'required|string',
        ];
    }
    public function messages(){
        return [
            'name.unique'=>'Este nombre de rol ya esta registrado',
            'name.required'=>'El rol es requerido',
            'description.required'=>'la descripción del rol es requerida',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class contribuirArtistaCreateRequest extends FormRequest
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
            'name'=>'required|string|unique:contribucion_artista|unique:artistas',
            'single-image'=>'required|mimes:jpeg,jpg,png',
            'pais'=>'required|numeric|not_in:none'
        ];
    }
    public function messages(){
        return [
            'name.unique'=>'Este nombre de artista ya esta registrado',
            'name.required'=>'El artista es requerido',
            'single-image.required'=>'La imagen del artista es requerida',
            'single-image.mimes'=>'La extención de archivo debe ser jpeg',
            'pais.numeric'=>'No selecciono el pais del artista'
        ];
    }
}

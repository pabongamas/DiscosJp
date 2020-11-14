<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class contribuirAlbumCreateRequest extends FormRequest
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
            'name'=>'required|string|',
            'anio'=>'required|string|',
            'single-image'=>'required|mimes:jpeg,jpg,png',
            'genero'=>'required|numeric|not_in:none',
            'artista'=>'required|numeric|not_in:none'
        ];
    }
    public function messages(){
        return [
            
            'name.required'=>'El album es requerido',
            'single-image.required'=>'La imagen del album es requerida',
            'single-image.mimes'=>'La extención de archivo debe ser jpeg',
            'genero.numeric'=>'No selecciono el genero del artista',
            'artista.numeric'=>'No selecciono el artista del album',
            'artista.required'=>'El artista es requerido',
            'artista.not-in'=>'El artista es requerido'
        ];
    }
}

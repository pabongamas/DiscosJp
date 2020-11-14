<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class contribucion_album extends Model
{
    use HasFactory;
    protected $table='contribucion_albums';
    public $timestamps = true;
    protected $fillable = ['name', 'image','anio','id_genero','id_artista','id_user'];
}

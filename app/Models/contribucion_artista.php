<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class contribucion_artista extends Model
{
    use HasFactory;
    protected $table='contribucion_artista';
    public $timestamps = true;
    protected $fillable = ['name', 'image','id_pais','id_user'];
}

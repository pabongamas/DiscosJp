<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class contribucion_genero extends Model
{
    use HasFactory;
    protected $table='contribucion_generos';
    public $timestamps = true;
    protected $fillable = ['name','id_user'];
}

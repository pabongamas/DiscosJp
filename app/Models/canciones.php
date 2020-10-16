<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class canciones extends Model
{
    protected $table='canciones';
    public $timestamps = false;
    protected $fillable = ['numero_cancion','name','minutos'];
}

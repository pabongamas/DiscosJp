<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class album_artista extends Model
{
    protected $table='album_artista';
    public $timestamps = false;
    protected $fillable = ['artista_id', 'album_id'];
}

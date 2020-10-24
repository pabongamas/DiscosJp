<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class artista extends Model
{
    public $timestamps = false;
    protected $fillable = ['name'];
    protected $table='artistas';
}

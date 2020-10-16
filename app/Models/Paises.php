<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paises extends Model
{
    protected $fillable = ['name'];

    public function artistas() {
        return $this
            ->belongsToMany('App\artista'); 
            
    }
}

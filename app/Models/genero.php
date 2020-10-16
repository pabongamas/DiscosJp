<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class genero extends Model
{
    protected $table='genero';
    public $timestamps = false;
    protected $fillable = ['name'];
}

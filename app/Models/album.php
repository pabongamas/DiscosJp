<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class album extends Model
{
    protected $table='album';
    public $timestamps = false;
    protected $fillable = ['name'];
}

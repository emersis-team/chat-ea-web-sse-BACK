<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Novedad extends Model
{
    protected $table = 'news';

    protected $fillable = [
        'titulo','descripcion', 'activo'
    ];

    //Get files for the novedad
    public function files()
    {
        return $this->hasMany('App\Models\File')->orderBy('created_at');
    }
}

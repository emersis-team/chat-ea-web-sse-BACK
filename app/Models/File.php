<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = [
        'file',
        'original_file',
        'description',
        'attachable_type',
        'attachable_id',
        'novedad_id',
    ];

    //Get novedad for the file
    public function novedad()
    {
        return $this->belongsTo('App\Models\Novedad');
    }

    //Get the owning attachable model.
    public function attachable()
    {
        return $this->morphTo();
    }
}

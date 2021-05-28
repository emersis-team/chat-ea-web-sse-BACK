<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PositionMessage extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'lat',
        'lon',
        'alt'
    ];
}

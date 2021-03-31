<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TextMessage extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'text'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FileMessage extends Model
{
    public $timestamps = false;

    protected $with = [
        'files',
    ];

    public function files()
    {
        return $this->morphMany('App\Models\File', 'attachable');
    }
}

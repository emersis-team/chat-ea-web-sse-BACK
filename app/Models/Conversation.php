<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = [
        'user_id_1', 'user_id_2'
    ];

    //Get messages for the conversation
    public function messages()
    {
        return $this->hasMany('App\Models\Message')->orderBy('created_at','desc');
    }

    //Get messages NO READ for the conversation
    public function messages_no_read()
    {
        return $this->hasMany('App\Models\Message')->where('read_at',NULL);
    }

    //Get usuario_id_1 for the conversation
    public function user_1()
    {
        return $this->belongsTo('App\User','user_id_1');
    }

    //Get usuario_id_2 for the conversation
    public function user_2()
    {
        return $this->belongsTo('App\User','user_id_2');
    }

}

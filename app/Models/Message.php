<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Message extends Model
{
    protected $fillable = [
        'message',
        'conversation_id',
        'sender_id',
        'receiver_id',
        'message_type',
        'message_id',
        'read_at'
    ];

    protected $with = [
        'message',
    ];

    public function message()
    {
        return $this->morphTo();
    }

    //Get conversation for the message
    public function conversation()
    {
        return $this->belongsTo('App\Models\Conversation');
    }

    //Get sender for the message
    public function sender()
    {
        return $this->belongsTo('App\User', 'sender_id');
    }
}

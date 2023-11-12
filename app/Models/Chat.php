<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Chat extends Model
{
    use HasFactory;
    protected $fillable = [
        'last_message_id',
        'name',
        'type'
    ];

    public function lastMessage()
    {
        return $this->hasOne(Message::class, 'last_message_id');
    }
    public function users()
    {
        return $this->hasManyThrough(User::class, ChatUser::class, 'chat_id', 'id', 'id', 'user_id');
    }
    public function messages()
    {
        return $this->hasMany(Message::class, 'chat_id', 'id');
    }
}

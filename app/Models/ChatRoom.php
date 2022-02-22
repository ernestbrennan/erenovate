<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatRoom extends Model
{
    protected $table = 'guarantee_chat_room';

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function unread_messages()
    {
        return $this->hasMany(Message::class)
            ->where('receiver_seen', Message::RECEIVER_NOT_SEEN)
            ->where('sender_id', '!=', \Auth::id());
    }

    public function last_message()
    {
        return $this->hasOne(Message::class)->orderBy('id', 'desc');
    }

    public function files()
    {
        return $this->hasManyThrough(File::class, Message::class);
    }

    public function guarantee_project()
    {
        return $this->hasOne(GuaranteeProject::class);
    }
}

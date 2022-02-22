<?php

namespace App\Travel\Chats;

use App\Travel\Projects\GuaranteeProject;
use App\Travel\Messages\Message;
use App\Travel\Files\File;
use Auth;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Travel\Chat\Chat
 *
 * @property-read GuaranteeProject $guarantee_project
 * @property-read Message $messages
 * @property-read File $files
 * @mixin \Eloquent
 */
class Chat extends Model
{
    protected $table = 'guarantee_chat_room';

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function unread_messages()
    {
        return $this->messages()->where('receiver_seen', 0)->where('sender_id', '!=', Auth::id());
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

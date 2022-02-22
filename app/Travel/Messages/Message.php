<?php

namespace App\Travel\Messages;

use App\Travel\Chats\Chat;
use App\Travel\Files\File;
use App\Travel\SystemNotifications\AbstractSystemNotification;
use App\Travel\SystemNotifications\SystemNotification;
use App\Travel\Users\User;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    const UPDATED_AT = null;

    const RECEIVER_NOT_SEEN = 0;
    const RECEIVER_SEEN = 1;

    const TYPE_TEXT = 'text';
    const TYPE_SYSTEM = 'system';
    const TYPE_ADMIN = 'admin';

    protected $table = 'guarantee_message';

    protected $fillable = [
        'content', 'sender_id', 'chat_id', 'receiver_seen', 'type'
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }

    public function files()
    {
        return $this->belongsToMany(File::class, 'guarantee_message_file', 'message_id', 'file_id');
    }

    public function notification()
    {
        return $this->hasOne(SystemNotification::class);
    }

    public static function getNotificationsByProject($guarantee_project_id, $with = [])
    {
        return self::with( array_merge($with, ['notification']))
            ->whereHas('chat.guarantee_project', function ($query) use ($guarantee_project_id) {
                $query->where('id', $guarantee_project_id);
            })
            ->where('type', self::TYPE_SYSTEM)
            ->get()
            ->each(function ($message) {
                $notification = $message->notification;
                if (!$notification) return;


                $notification->loadTarget();

                $notification['title'] = $notification->getTitle();
            });
    }

    public static function geTotalMessagesCountByChat($guarantee_project_id)
    {
        return self::query()
            ->whereHas('chat_room.guarantee_project', function ($query) use ($guarantee_project_id) {
                $query->where('id', $guarantee_project_id);
            })
            ->count();
    }
}

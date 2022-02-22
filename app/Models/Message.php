<?php

namespace App\Models;

use App\Travel\SystemNotifications\AbstractSystemNotification;
use App\Travel\SystemNotifications\SystemNotification;
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

    protected $hidden = [
        'chat_id'
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function chat_room()
    {
        return $this->belongsTo(ChatRoom::class);
    }

    public function files()
    {
        return $this->belongsToMany(File::class, 'guarantee_message_file', 'message_id', 'file_id');
    }

    public function notification()
    {
        return $this->hasOne(SystemNotification::class);
    }

    public function guarantee_project()
    {
        return $this->hasManyThrough(
            GuaranteeProject::class,
            ChatRoom::class,
            'id',
            'id',
            'chat_id',
            'guarantee_project_id'
        );
    }

    public static function getMessagesByChat($chat_room_id, $limit = 15, $offset = 0)
    {
        return self::with([
            'files',
            'notification',
        ])
            ->whereHas('chat_room', function ($query) use ($chat_room_id) {
                $query->where('id', $chat_room_id);
            })
            ->offset($offset)
            ->limit($limit)
            ->orderBy('id', 'desc')
            ->get()
            ->each(function ($message) {
                $notification = $message->notification;
                if (!$notification) return;

                if (in_array($notification['type'], AbstractSystemNotification::CONTRACT)) {
                    $notification->load('contract');
                }
                if (in_array($notification['type'], AbstractSystemNotification::CONTRACT_DRAFT)) {
                    $notification->load('contract_draft');

                } else if (in_array($notification['type'], AbstractSystemNotification::MILESTONE_CONTENT)) {
                    $notification->load('milestone_content.milestone');

                } else if (in_array($notification['type'], AbstractSystemNotification::MATERIALS_CONTENT)) {
                    $notification->load('materials_content');

                } else if (in_array($notification['type'], AbstractSystemNotification::INVOICE)) {
                    $notification->load('invoice.milestone');

                } else if (in_array($notification['type'], AbstractSystemNotification::ISSUE)) {
                    $notification->load('issue');
                }

                $notification['title'] = $notification->getTitle();
            });
    }
    public static function getNotificationsByProject($guarantee_project_id, $with = [])
    {
        return self::with( array_merge($with, ['notification']))
            ->whereHas('chat_room.guarantee_project', function ($query) use ($guarantee_project_id) {
                $query->where('id', $guarantee_project_id);
            })
            ->where('type', self::TYPE_SYSTEM)
            ->get()
            ->each(function ($message) {
                $notification = $message->notification;
                if (!$notification) return;

                if (in_array($notification['type'], AbstractSystemNotification::CONTRACT)) {
                    $notification->load('contract');
                }
                if (in_array($notification['type'], AbstractSystemNotification::CONTRACT_DRAFT)) {
                    $notification->load('contract_draft');

                } else if (in_array($notification['type'], AbstractSystemNotification::MILESTONE_CONTENT)) {
                    $notification->load('milestone_content.milestone');

                } else if (in_array($notification['type'], AbstractSystemNotification::MATERIALS_CONTENT)) {
                    $notification->load('materials_content');

                } else if (in_array($notification['type'], AbstractSystemNotification::INVOICE)) {
                    $notification->load('invoice.milestone');

                } else if (in_array($notification['type'], AbstractSystemNotification::ISSUE)) {
                    $notification->load('issue');
                }

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

    public function attachFiles($files)
    {
        foreach ($files as $file) {

            $this->files()->attach($file['id']);
        }
    }
}

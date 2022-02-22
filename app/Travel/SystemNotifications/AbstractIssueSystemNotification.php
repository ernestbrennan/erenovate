<?php

namespace App\Travel\SystemNotifications;

use App\Events\Broadcast\SendMessageEvent;
use App\Events\Mail\SystemNotificationEmailEvent;
use App\Travel\Messages\Message;

abstract class AbstractIssueSystemNotification extends AbstractSystemNotification
{
    public static function doNotification($issue, $comment = '')
    {
        $project_chat = $issue->guarantee_project->chat;
        $issue_chat = $issue->chat;

        $message_init = [
            'sender_id' => \Auth::id() ?? 0,
            'content' => $comment,
            'type' => Message::TYPE_SYSTEM,
            'receiver_seen' => Message::RECEIVER_NOT_SEEN
        ];

        $project_message = Message::query()->create(array_merge($message_init, [ 'chat_id' => $project_chat->id]));
        $issue_message = Message::query()->create(array_merge($message_init, [ 'chat_id' => $issue_chat['id']]));

        $project_notification = static::createNotification($issue['id'], $project_message['id'], static::getType());
        $issue_notification = static::createNotification($issue['id'], $issue_message['id'], static::getType());

        $project_message->load(['files', 'notification']);
        $issue_message->load(['files', 'notification']);

        broadcast(new SendMessageEvent($project_message))->toOthers();
        broadcast(new SendMessageEvent($issue_message))->toOthers();

        event(new SystemNotificationEmailEvent($project_notification));
    }

    protected static function createNotification($issue_id, $message_id, $type)
    {
        return self::saveSystemNotification([
            'message_id' => $message_id,
            'issue_id' => $issue_id,
            'type' => $type,
        ]);
    }
}

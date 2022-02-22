<?php

namespace App\Travel\SystemNotifications;

use App\Events\Broadcast\SendMessageEvent;
use App\Events\Mail\SystemNotificationEmailEvent;
use App\Travel\Messages\Message;

abstract class AbstractInvoiceSystemNotification extends AbstractSystemNotification
{
    public static function doNotification($invoice, $comment = '', $files = [])
    {
        $chat = $invoice->milestone->contract_draft->contract->guarantee_project->chat;

        $message = Message::query()
            ->create([
                'sender_id' => \Auth::id(),
                'chat_id' => $chat->id,
                'content' => $comment,
                'type' => Message::TYPE_SYSTEM,
                'receiver_seen' => Message::RECEIVER_NOT_SEEN
            ]);

        if (!empty($files)) {
            $message->files()->attach(array_pluck($files, 'id'));
        }

        $systemNotification = static::createInvoiceSystemNotification($invoice->id, $message['id'], static::getType());

        $message->load(['files', 'notification']);

        broadcast(new SendMessageEvent($message))->toOthers();
        event(new SystemNotificationEmailEvent($systemNotification));

        return $systemNotification;
    }

    protected static function createInvoiceSystemNotification($invoice_id, $message_id, $type)
    {
        return self::saveSystemNotification([
            'message_id' => $message_id,
            'type' => $type,
            'invoice_id' => $invoice_id
        ]);
    }
}

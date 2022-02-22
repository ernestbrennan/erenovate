<?php

namespace App\Events\Broadcast;

use App\Travel\SystemNotifications\AbstractSystemNotification;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Broadcasting\{
    Channel, InteractsWithSockets
};

class SendMessageEvent implements ShouldBroadcast
{
   use InteractsWithSockets, SerializesModels;

    public $queue = 'broadcast';

    public $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return new Channel('Chat.'. $this->message->chat->id);
    }

    public function broadcastAs()
    {
        return 'new_message';
    }

    public function broadcastWith()
    {
        $message = $this->message->load(['files', 'notification']);
        
        if (!empty($message->notification)) {
            $message->notification->loadTarget();
            $message->notification['title'] = $message->notification->getTitle();

            $need_update_guarantee_project = in_array($message->notification['type'], [
                AbstractSystemNotification::TYPE_CONTRACT_DRAFT_OFFERED,
                AbstractSystemNotification::TYPE_CONTRACT_DRAFT_REJECTED,
                AbstractSystemNotification::TYPE_CONTRACT_BOTH_ACCEPTED,
                AbstractSystemNotification::TYPE_CONTRACT_SIGNED,
                AbstractSystemNotification::TYPE_CONTRACT_COMPLETED,
                AbstractSystemNotification::TYPE_SUMMARY_READY,
            ]);

            if ($need_update_guarantee_project) {

                $message->notification['guarantee_project'] = $this->message->chat->guarantee_project->getData();
            }
        }

        return $message->toArray();
    }
}
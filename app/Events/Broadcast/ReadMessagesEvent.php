<?php

namespace App\Events\Broadcast;

use App\Travel\Messages\Message;
use App\Travel\Users\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Broadcasting\{
    Channel, InteractsWithSockets
};

class ReadMessagesEvent implements ShouldBroadcast
{
   use InteractsWithSockets, SerializesModels;

    public $queue = 'broadcast';

    public $message;
    public $interlocutor;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return new Channel('Chat.'. $this->message->chat_id);
    }

    public function broadcastAs()
    {
        return 'read_message';
    }

    public function broadcastWith()
    {
        return  $this->message->toArray();
    }
}
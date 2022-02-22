<?php

namespace App\Travel\Messages\Observers;

use App\Events\CreatedMessageEvent;
use App\Travel\Messages\Message;

class MessageObserver
{
    public function created(Message $message)
    {
        event(new CreatedMessageEvent($message));
    }
}
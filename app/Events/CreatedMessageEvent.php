<?php

namespace App\Events;

use App\Travel\Messages\Message;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;

class CreatedMessageEvent implements ShouldQueue
{
    use SerializesModels;

    public $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }
}

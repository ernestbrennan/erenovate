<?php

namespace App\Events\Mail;

use App\Travel\SystemNotifications\SystemNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SystemNotificationEmailEvent implements ShouldQueue
{
    public $notification;

    public function __construct(SystemNotification $notification)
    {
        $this->notification = $notification;
    }
}

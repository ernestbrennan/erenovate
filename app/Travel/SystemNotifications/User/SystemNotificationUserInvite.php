<?php

namespace App\Travel\SystemNotifications\User;

use App\Travel\SystemNotifications\AbstractUserSystemNotification;

class SystemNotificationUserInvite extends AbstractUserSystemNotification
{
    public static function getType(): string
    {
        return parent::TYPE_USER_INVITE;
    }
}

<?php

namespace App\Travel\SystemNotifications\Issue;

use App\Travel\SystemNotifications\AbstractIssueSystemNotification;

class SystemNotificationAdminJoined extends AbstractIssueSystemNotification
{
    public static function getType():string
    {
        return parent::TYPE_ADMIN_JOINED;
    }
}

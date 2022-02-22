<?php

namespace App\Travel\SystemNotifications\Issue;

use App\Travel\SystemNotifications\AbstractIssueSystemNotification;

class SystemNotificationIssueClosed extends AbstractIssueSystemNotification
{
    public static function getType():string
    {
        return parent::TYPE_ISSUE_CLOSED;
    }
}

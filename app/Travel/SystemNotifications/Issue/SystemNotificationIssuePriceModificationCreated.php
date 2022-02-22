<?php

namespace App\Travel\SystemNotifications\Issue;

use App\Travel\SystemNotifications\AbstractIssueSystemNotification;

class SystemNotificationIssuePriceModificationCreated extends AbstractIssueSystemNotification
{
    public static function getType():string
    {
        return parent::TYPE_ISSUE_PRICE_MODIFICATION_CREATED;
    }
}

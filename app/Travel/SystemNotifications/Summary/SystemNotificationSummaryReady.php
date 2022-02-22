<?php

namespace App\Travel\SystemNotifications\Summary;

use App\Travel\SystemNotifications\AbstractSummarySystemNotification;

class SystemNotificationSummaryReady extends AbstractSummarySystemNotification
{
    public static function getType():string
    {
        return parent::TYPE_SUMMARY_READY;
    }
}

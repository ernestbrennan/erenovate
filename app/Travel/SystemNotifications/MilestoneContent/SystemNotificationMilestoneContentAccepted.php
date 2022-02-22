<?php

namespace App\Travel\SystemNotifications\MilestoneContent;

use App\Travel\SystemNotifications\AbstractMilestoneContentSystemNotification;

class SystemNotificationMilestoneContentAccepted extends AbstractMilestoneContentSystemNotification
{
    public static function getType():string
    {
       return parent::TYPE_MILESTONE_CONTENT_ACCEPTED;
    }
}

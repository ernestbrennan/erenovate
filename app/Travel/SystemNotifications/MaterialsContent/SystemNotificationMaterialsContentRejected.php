<?php

namespace App\Travel\SystemNotifications\Invoice;

use App\Travel\SystemNotifications\AbstractMaterialsContentSystemNotification;

class SystemNotificationMaterialsContentRejected extends AbstractMaterialsContentSystemNotification
{
    public static function getType():string
    {
       return parent::TYPE_MATERIALS_CONTENT_REJECTED;
    }
}

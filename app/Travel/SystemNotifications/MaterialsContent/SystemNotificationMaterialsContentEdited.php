<?php

namespace App\Travel\SystemNotifications\MaterialsContent;

use App\Travel\SystemNotifications\AbstractMaterialsContentSystemNotification;

class SystemNotificationMaterialsContentEdited extends AbstractMaterialsContentSystemNotification
{
    public static function getType():string
    {
       return parent::TYPE_MATERIALS_CONTENT_EDITED;
    }
}

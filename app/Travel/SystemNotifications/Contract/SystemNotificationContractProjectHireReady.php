<?php

namespace App\Travel\SystemNotifications\Contract;

use App\Travel\SystemNotifications\AbstractContactSystemNotification;

class SystemNotificationContractProjectHireReady extends AbstractContactSystemNotification
{
    public static function getType():string
    {
       return parent::TYPE_CONTRACT_PROJECT_HIRE_READY;
    }
}

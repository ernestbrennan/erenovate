<?php

namespace App\Travel\SystemNotifications\Contract;

use App\Travel\SystemNotifications\AbstractContactSystemNotification;

class SystemNotificationContractBothAccepted extends AbstractContactSystemNotification
{
    public static function getType():string
    {
       return parent::TYPE_CONTRACT_BOTH_ACCEPTED;
    }
}

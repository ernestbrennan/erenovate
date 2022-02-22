<?php

namespace App\Travel\SystemNotifications\Contract;

use App\Travel\SystemNotifications\AbstractContactSystemNotification;

class SystemNotificationContractSigned extends AbstractContactSystemNotification
{
    public static function getType(): string
    {
        return parent::TYPE_CONTRACT_SIGNED;
    }
}

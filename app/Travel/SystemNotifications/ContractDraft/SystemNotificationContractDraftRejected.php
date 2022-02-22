<?php

namespace App\Travel\SystemNotifications\ContractDraft;

use App\Travel\SystemNotifications\AbstractContactDraftSystemNotification;

class SystemNotificationContractDraftRejected extends AbstractContactDraftSystemNotification
{
    public static function getType():string
    {
        return parent::TYPE_CONTRACT_DRAFT_REJECTED;
    }
}

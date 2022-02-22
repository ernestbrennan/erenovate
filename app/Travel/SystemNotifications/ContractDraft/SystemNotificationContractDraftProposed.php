<?php

namespace App\Travel\SystemNotifications\ContractDraft;

use App\Travel\SystemNotifications\AbstractContactDraftSystemNotification;

class SystemNotificationContractDraftProposed extends AbstractContactDraftSystemNotification
{
    public static function getType():string
    {
        return parent::TYPE_CONTRACT_DRAFT_PROPOSED;
    }
}

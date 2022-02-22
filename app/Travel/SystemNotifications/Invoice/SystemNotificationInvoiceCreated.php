<?php

namespace App\Travel\SystemNotifications\Invoice;

use App\Travel\SystemNotifications\AbstractInvoiceSystemNotification;

class SystemNotificationInvoiceCreated extends AbstractInvoiceSystemNotification
{
    public static function getType():string
    {
       return parent::TYPE_INVOICE_CREATED;
    }
}

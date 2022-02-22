<?php

namespace App\Travel\SystemNotifications\Invoice;

use App\Travel\SystemNotifications\AbstractInvoiceSystemNotification;

class SystemNotificationInvoiceConfirmed extends AbstractInvoiceSystemNotification
{
    public static function getType():string
    {
       return parent::TYPE_INVOICE_CONFIRMED;
    }
}

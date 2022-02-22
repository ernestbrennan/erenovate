<?php

namespace App\Exceptions\Invoice;

use Exception;

class InvoiceOverdueException extends Exception
{
    public $invoice;

    /**
     * InvoiceOverdueException constructor.
     * @param $invoice
     */
    public function __construct($invoice)
    {
        $this->invoice = $invoice;
    }
}
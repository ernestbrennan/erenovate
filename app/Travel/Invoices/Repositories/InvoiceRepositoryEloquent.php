<?php

namespace App\Travel\Invoices\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use App\Travel\Invoices\Repositories\Interfaces\InvoiceRepositoryInterface;
use App\Travel\Invoices\Invoice;

class InvoiceRepositoryEloquent extends BaseRepository implements InvoiceRepositoryInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string
    {
        return Invoice::class;
    }
}
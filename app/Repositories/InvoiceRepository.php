<?php

namespace App\Repositories;

use App\Exceptions\Invoice\InvoiceOverdueException;
use App\Travel\Invoices\Invoice;
use DateTime;

class InvoiceRepository extends Invoice
{
    public static function findById($invoice_id)
    {
        return self::query()->find($invoice_id)->first();
    }

    public function validateDueDate()
    {
        $date_now = new DateTime();
        $date2 = new DateTime($this->due_date);

        if ($date_now > $date2) {
            throw new InvoiceOverdueException($this);
        }
    }

    public function getGuaranteeProject()
    {
        return $this->milestone->contract_draft->contract->guarantee_project;
    }

    public static function getWithRelations($invoice_id)
    {
        return self::with([
            'milestone',
            'batches',
            'batches.user',
            'batches.files',
            'works',
            'materials',
            'material_files',
        ])
            ->find($invoice_id);
    }

    public static function deleteWithRelations($invoice)
    {
        foreach ($invoice['batches'] as $batch) {
            $batch->delete();
        }
        foreach ($invoice['works'] as $work) {
            $work->delete();
        }

        foreach ($invoice['materials'] as $material) {
            $material->delete();
        }

        foreach ($invoice['material_files'] as $material_file) {
            $material_file->delete();
        }

        $invoice->delete();
    }
}

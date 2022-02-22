<?php

namespace App\Services;

use App\Exceptions\GuaranteeProjectInvalidAccessException;
use App\Travel\Batches\Batch;
use App\Travel\Invoices\Invoice;
use App\Travel\Materials\Material;
use App\Travel\SystemNotifications\Invoice\SystemNotificationInvoiceCreated;
use App\Travel\Works\Work;

class InvoiceService
{
    /**
     * @param $milestone_id
     * @param $invoice
     * @param $comment
     * @return $this|\Illuminate\Database\Eloquent\Model
     * @throws GuaranteeProjectInvalidAccessException
     */
    public static function createInvoice($invoice, $comment)
    {
        $batches = $invoice['batches'];
        $works = $invoice['works'];
        $material_files = $invoice['material_files'];
        $materials = $invoice['materials'];

        $milestone = $invoice['milestone'];

        $invoice = Invoice::query()->create([
            'title' => $invoice['title'],
            'description' => $invoice['description'],
            'number' => $invoice['number'],
            'taxes' => $invoice['taxes'],
            'total_price' => $invoice['total_price'],
            'status' => Invoice::STATUS_PENDING,
            'milestone_id' => $milestone['id'],
            'due_date' => $invoice['due_date'],
            'creation_date' => $invoice['creation_date'],
        ]);

        $invoice->material_files()->attach(array_pluck($material_files, 'id'));

        foreach ($batches as $batch) {

            $new_batch = Batch::query()
                ->create([
                    'user_id' => $batch['user']['userId'],
                    'description' => $batch['description']
                ]);

            $new_batch->files()->attach(array_pluck($batch['files'], 'id'));
            $invoice->batches()->attach([$new_batch['id']]);
        }

        foreach ($works as $work) {
            Work::query()->create([
                'invoice_id' => $invoice['id'],
                'title' => $work['title'],
                'price' => $work['price'],
                'quantity' => $work['quantity']
            ]);
        }

        foreach ($materials as $material) {
            if (!empty($material['title'])) {

                $new_material = Material::query()->create($material);
                $invoice->materials()->attach([$new_material['id']]);
            }
        }

        SystemNotificationInvoiceCreated::doNotification($invoice, $comment);

        return $invoice;
    }
}

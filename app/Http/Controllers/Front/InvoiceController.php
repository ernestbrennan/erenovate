<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Exceptions\GuaranteeProjectInvalidAccessException;
use App\Http\Requests\Invoice as R;
use App\Services\Snappy\Pdf;
use App\Services\TimelineService;
use App\Services\InvoiceService;
use App\Travel\Contracts\Contract;
use App\Travel\ContractsDraft\ContractDraft;
use App\Travel\Projects\GuaranteeProject;
use App\Travel\Invoices\Invoice;
use App\Travel\Milestones\Milestone;
use App\Travel\Milestones\MilestoneContent;
use App\Travel\SystemNotifications\Invoice\{
    SystemNotificationInvoiceCompleted,
    SystemNotificationInvoiceConfirmed,
    SystemNotificationInvoiceRejected,
    SystemNotificationInvoiceUnverified,
};
use App\Travel\SystemNotifications\Summary\SystemNotificationSummaryReady;

class InvoiceController extends Controller
{
    public function getList(R\GetListRequest $request)
    {
        $guarantee_project_id = $request->input('guarantee_project_id');

        $guarantee_project = GuaranteeProject::with([
            'contract',
            'contract.last_draft',
            'contract.last_draft.invoices' => function ($query) {
                $query->orderBy('id', 'desc');
            },
            'contract.last_draft.invoices.milestone',

        ])
            ->find($guarantee_project_id);

        return [
            'invoices' => $guarantee_project->contract->last_draft->invoices,
            'timeline' => TimelineService::getTimeLine($guarantee_project->contract['id']),
            'has_current_invoice' => (boolean)count($guarantee_project->contract->last_draft->current_invoice),
            'guarantee_project' => $guarantee_project->getData()
        ];
    }

    public function getNew(R\GetNewRequest $request)
    {
        $guarantee_project_id = $request->input('guarantee_project_id');

        $guarantee_project = GuaranteeProject::with([
            'contract',
            'contract.last_draft',
            'contract.last_draft.current_milestone',
            'contract.last_draft.current_milestone.milestone_content' => function ($query) {
                $query->where('status', MilestoneContent::STATUS_ACTIVE);
            },
            'contract.last_draft.current_milestone.milestone_content.batches',
            'contract.last_draft.current_milestone.milestone_content.batches.user',
            'contract.last_draft.current_milestone.milestone_content.batches.files',
            'contract.last_draft.current_milestone.milestone_content.materials',
            'contract.last_draft.current_milestone.milestone_content.material_files',
        ])
            ->find($guarantee_project_id);

        $milestone = $guarantee_project['contract']['last_draft']['current_milestone'];

        $invoice = [
            'title' => '',
            'description' => '',
            'number' => '',
            'due_date' => null,
            'creation_date' => null,
            'taxes' => 0,
            'total_price' => 0,
            'materials' => $milestone['milestone_content']['materials'],
            'material_files' => $milestone['milestone_content']['material_files'],
            'batches' => [],
            'works' => [
                [
                    'title' => $milestone['milestone_content']['title'],
                    'quantity' => 1,
                    'price' => $milestone['milestone_content']['price']
                ]
            ],
            'milestone' => $milestone
        ];

        return [
            'invoice' => $invoice,
            'timeline' => TimelineService::getTimeLine($guarantee_project['contract']['id']),
            'guarantee_project' => $guarantee_project->getData()
        ];
    }

    public function getCurrent(R\GetCurrentRequest $request)
    {
        $guarantee_project_id = $request->input('guarantee_project_id');

        $guarantee_project = GuaranteeProject::with([
            'contract.last_draft',
            'contract.last_draft.current_milestone.milestone_content',
            'contract.last_draft.current_milestone.milestone_content.batches',
            'contract.last_draft.current_milestone.milestone_content.batches.user',
            'contract.last_draft.current_milestone.milestone_content.batches.files',
            'contract.last_draft.current_milestone.invoice',
            'contract.last_draft.current_milestone.invoice',
            'contract.last_draft.current_milestone.invoice.batches',
            'contract.last_draft.current_milestone.invoice.batches.user',
            'contract.last_draft.current_milestone.invoice.batches.files',
            'contract.last_draft.current_milestone.invoice.works',
            'contract.last_draft.current_milestone.invoice.materials',
            'contract.last_draft.current_milestone.invoice.material_files',
        ])
            ->find($guarantee_project_id);

        $invoice = $guarantee_project->contract->last_draft->current_milestone->invoice;
        $invoice['milestone'] = $guarantee_project->contract->last_draft->current_milestone->only('id', 'sequence', 'milestone_content');

        return [
            'invoice' => $invoice,
            'timeline' => TimelineService::getTimeLine($guarantee_project->contract['id'], $invoice['id']),
            'guarantee_project' => $guarantee_project->getData(),
        ];
    }

    public function getHistory(R\GetCurrentRequest $request)
    {
        $invoice_id = $request->input('invoice_id');

        $invoice = Invoice::with([
            'milestone',
            'milestone.milestone_content',
            'milestone.milestone_content.batches',
            'milestone.milestone_content.batches.user',
            'milestone.milestone_content.batches.files',
            'batches',
            'batches.user',
            'batches.files',
            'works',
            'materials',
            'material_files',
        ])
            ->find($invoice_id);

        $contract = $invoice->milestone->contract_draft->contract;

        return [
            'invoice' => $invoice,
            'timeline' => TimelineService::getTimeLine($contract['id'], $invoice_id),
            'guarantee_project' => $contract->guarantee_project->getData(),
        ];

    }

    public function create(R\CreateRequest $request)
    {
        $invoice = $request->input('invoice');
        $comment = $request->input('comment');

        try {
            return [
                'status_code' => 200,
                'response' => [
                    'invoice' => InvoiceService::createInvoice($invoice, $comment)
                ]
            ];
        } catch (GuaranteeProjectInvalidAccessException $e) {
            return [
                'status_code' => 403,
                'response' => []
            ];
        }
    }

    public function reject(R\RejectInvoiceRequest $request)
    {
        $invoice_id = $request->input('invoice_id');
        $comment = $request->input('comment');

        $invoice = Invoice::query()->find($invoice_id);

        $invoice->update([
            'status' => Invoice::STATUS_REJECTED
        ]);

        SystemNotificationInvoiceRejected::doNotification($invoice, $comment);

        return [
            'status_code' => 200
        ];
    }

    public function confirm(R\AcceptInvoiceRequest $request)
    {
        $invoice_id = $request->input('invoice_id');
        $payment_description = $request->input('payment_details');

        $invoice = Invoice::find($invoice_id);

        $invoice->update([
            'status' => Invoice::STATUS_CONFIRMED
        ]);

        $comment = $payment_description['comment'] ?? '';

        SystemNotificationInvoiceConfirmed::doNotification($invoice, $comment, $payment_description['files']);

        return [
            'status_code' => 200
        ];
    }

    public function unverify(R\AcceptInvoiceRequest $request)
    {
        $invoice_id = $request->input('invoice_id');
        $comment = $request->input('comment');

        $invoice = Invoice::find($invoice_id);

        $invoice->update([
            'status' => Invoice::STATUS_UNVERIFIED
        ]);

        SystemNotificationInvoiceUnverified::doNotification($invoice, $comment);


        return [
            'status_code' => 200
        ];
    }

    public function complete(R\AcceptInvoiceRequest $request)
    {
        $invoice_id = $request->input('invoice_id');
        $comment = $request->input('comment');

        $invoice = Invoice::find($invoice_id);

        $invoice->update([
            'status' => Invoice::STATUS_COMPLETED
        ]);

        $invoice->milestone->update([
            'status' => Milestone::STATUS_COMPLETED
        ]);

        $contract_draft = ContractDraft::with([
            'contract',
            'milestones' => function ($query) {
                $query->where('status', '!=', Milestone::STATUS_COMPLETED);
            }
        ])
            ->find($invoice->milestone['contract_draft_id']);

        SystemNotificationInvoiceCompleted::doNotification($invoice, $comment);

        if (!$contract_draft->milestones->count()) {


            $contract_draft->contract->update([
                'status' => Contract::STATUS_FINISHED
            ]);

            SystemNotificationSummaryReady::doNotification($contract_draft);

        } else {
            $contract_draft->milestones[0]->update([
                'status' => Milestone::STATUS_IN_PROGRESS
            ]);
        }

        return [
            'status_code' => 200
        ];
    }

    public function exportPdf(R\ExportPdfRequest $request)
    {
        $invoice = $request->input('invoice');
        $invoice_summary = $request->input('invoice_summary');

        $view = view('pdf.invoice.index', [
            'invoice' => $invoice,
            'invoice_summary' => $invoice_summary,
        ])
            ->render();

        $pdf_generator = new Pdf(env('WKHTMLTOPDF_COMMAND'));
        $pdf = $pdf_generator->getOutputFromHtml($view, getPdfGenerateParams());

        $filename = 'invoice_for_milestone_' . $invoice['milestone_id'] . '.pdf';

        return response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'File-Name' => urlencode($filename),

            'Content-Disposition' => sprintf('attachment; filename="%s"', $filename),
        ]);
    }
}

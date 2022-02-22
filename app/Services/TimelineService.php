<?php

namespace App\Services;

use App\Travel\Contracts\Contract;
use App\Travel\ContractsDraft\ContractDraft;
use App\Travel\Invoices\Invoice;

class TimelineService
{
    public static function getTimeLine($contact_id, $invoice_id = null)
    {
        $contact = Contract::with([
            'last_draft',
            'last_draft.milestones',
            'last_draft.milestones.milestone_content:title,start_date,end_date,milestone_id',
            'last_draft.milestones.invoice:title,created_at,milestone_id',
            'signed_notification',
        ])
            ->find($contact_id);

        $contract_draft = $contact['last_draft'];
        $timeline_milestones = [];

        if ($contract_draft->type == ContractDraft::TYPE_SEVERAL_MILESTONE) {
            $milestones = $contract_draft->milestones;

            foreach ($milestones as $milestone) {

                $timeline_milestones[] = [
                    'title' => 'Milestone ' . $milestone->sequence,
                    'status' => $milestone->status,
                    'type' => 'milestone',
                    'created_at' => $milestone->milestone_content->start_date,
                    'id' => $milestone->id
                ];
            }
        } else {

            $milestone = $contract_draft->milestones->first();

            $timeline_milestones[] = [
                'title' => 'Work in progress',
                'type' => 'milestone',
                'status' => $milestone->status,
                'id' => $milestone->id
            ];
        }
        $last_milestone = $contract_draft->milestones->last();

        $invoice = Invoice::find($invoice_id);

        if ($invoice) {
            $invoice = [
                'status' => $invoice['status'],
                'milestone_id' => $invoice->milestone->id,
                'title' => 'Payment Request'
            ];
        }

        $contract_status = $contact->status;

        return [
            'contract' => [
                'title' => 'Project Scope',
                'type' => 'contract',
                'status' => $contract_status,
                'created_at' => $contact->signed_notification->created_at->toDateTimeString(),
            ],
            'milestones' => $timeline_milestones,
            'completed' => [
                'title' => 'Completed',
                'type' => 'finish',
                'status' => $contract_status === 'completed' ? 'completed' : ($contract_status === 'finished' ? 'in_progress' : 'pending'),
                'created_at' => $last_milestone->milestone_content['end_date'],
            ],
            'invoice' => $invoice,

        ];
    }

}

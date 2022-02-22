<?php

namespace App\Travel\Summary\Services;

use App\Services\TimelineService;
use App\Travel\ContractsDraft\ContractDraft;
use App\Travel\Issues\Issue;
use App\Travel\Messages\Message;
use App\Travel\Milestones\MilestoneContent;
use App\Travel\Projects\GuaranteeProject;
use App\Travel\Users\User;
use Illuminate\Http\Request;

class SummaryService
{
    public function byProject(Request $request)
    {
        $guarantee_project_id = $request->input('id');

        $guarantee_project = GuaranteeProject::with([
            'contract',
            'issues' => function($query) {
                if (\Auth::user()['role'] === User::ROLE_CONTRACTOR) {
                    $query->where('type', Issue::TYPE_ISSUE);
                }
            },
        ])
            ->find($guarantee_project_id);

        if ($guarantee_project->contract['status'] !== 'pending' && $guarantee_project->contract['status'] !== 'signed') {
            $history = Message::getNotificationsByProject($guarantee_project_id);

            $summary_table = self::getSummaryTable($guarantee_project->contract['id']);
        }

        return [
            'timeline' => TimelineService::getTimeLine($guarantee_project->contract['id']),
            'guarantee_project' => array_merge($guarantee_project->getData(), $guarantee_project->project->only('project_name')),
            'summary' => [
                'contract' => $guarantee_project->contract,
                'issues' => $guarantee_project->issues,
                'history' => $history ?? null,
                'summary_table' => $summary_table ?? null,
                'price_modification' => Issue::where('guarantee_project_id', $guarantee_project_id)
                    ->where('type', Issue::TYPE_PRICE_MODIFICATION)
                    ->where('status', '!=', Issue::STATUS_CLOSED)
                    ->first()
            ],
        ];
    }

    public static function getSummaryTable($contract_id)
    {
        $contract_draft = ContractDraft::with([
            'summary',
            'milestones',
            'milestones.milestone_content' => function ($query) {
                $query->where('status', '!=', MilestoneContent::STATUS_REJECTED);
            },
            'milestones.milestone_content.materials',
            'milestones.invoice.works',
            'milestones.invoice.materials',
        ])
            ->where('contract_id', $contract_id)
            ->where('status', '!=', ContractDraft::STATUS_REJECTED)
            ->orderBy('id', 'desc')
            ->first();

        $payments = [];

        $payment_total = [
            'work' => 0,
            'material' => 0,
            'final_total' => 0,
            'estimated_total' => 0,
        ];

        foreach ($contract_draft->milestones as $milestone) {
            $payment = [
                'milestone_sequence' => $milestone['sequence'],
                'work' => [
                    'estimated' => $milestone->milestone_content['price'],
                    'final' => 0
                ],
                'material' => [
                    'estimated' => 0,
                    'final' => 0
                ]
            ];

            $payment_total['estimated_total'] += format_price($milestone->milestone_content['price']);

            if ($milestone->milestone_content->materials) {
                $buff = 0;
                foreach ($milestone->milestone_content->materials as $material) {
                    $buff += $material['quantity'] * format_price($material['price']);
                }

                $payment_total['estimated_total'] += $buff;
                $payment['material']['estimated'] = convert_price($buff);
            }

            if ($milestone->invoice) {
                if ($milestone->invoice->works) {
                    $buff = 0;
                    foreach ($milestone->invoice->works as $work) {
                        $buff += $work['quantity'] * format_price($work['price']);
                    }
                    $payment['work']['final'] = convert_price($buff);
                    $payment_total['work'] += $buff;
                }

                if ($milestone->invoice->materials) {
                    $buff = 0;
                    foreach ($milestone->invoice->materials as $material) {
                        $buff += $material['quantity'] * format_price($material['price']);

                    }
                    $payment['material']['final'] = convert_price($buff);
                    $payment_total['material'] += $buff;
                }
            }

            $payments[] = $payment;
        }
        $final_total = empty(format_price($contract_draft->summary['changed_total'])) ? convert_price($payment_total['material'] + $payment_total['work']) : $contract_draft->summary['changed_total'];

        $payment_total = [
            'final_work' => convert_price($payment_total['work']),
            'final_material' => convert_price($payment_total['material']),
            'final_total' => $final_total,
            'estimated_total' => convert_price($payment_total['estimated_total']),
        ];

        return compact('payments', 'payment_total');
    }

}

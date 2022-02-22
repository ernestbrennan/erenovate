<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContractDraftSummary;
use App\Models\GuaranteeProject;
use App\Travel\Summary\Services\SummaryService;
use Illuminate\Http\Request;

class SummaryController extends Controller
{
    public function getByProject(Request $request)
    {
        $guarantee_project_id = $request->input('guarantee_project_id');

        $guarantee_project = GuaranteeProject::with([
            'contract',
        ])
            ->find($guarantee_project_id);

        $summary = SummaryService::getSummaryTable($guarantee_project->contract['id']);

        // TODO hotfix, requires core file update: app/Services/SummaryService.php
        if ($summary['payment_total']['final_work'] == 0
            && !$summary['payment_total']['final_total'] == 0
            && $summary['payment_total']['estimated_total'] > 0)
        {
            $summary['payment_total']['final_work'] = $summary['payment_total']['estimated_total'];
            $summary['payment_total']['final_total'] = $summary['payment_total']['estimated_total'];
        }

        return [
            'summary' => $summary
        ];
    }

    public function changeTotal(Request $request)
    {
        $summary_id = $request->input('summary_id');
        $changed_total = $request->input('changed_total');

        ContractDraftSummary::find($summary_id)->update([
            'changed_total' => $changed_total
        ]);

        return [
            'status_code' => 200
        ];

    }
}

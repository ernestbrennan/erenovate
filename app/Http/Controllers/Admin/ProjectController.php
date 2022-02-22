<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GuaranteeProject;
use App\Models\Issue;
use App\Models\Milestone;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function getList(Request $request)
    {
        $query_search = $request->input('query_search', '');
        $state_search = $request->input('state_search', 'all');

        $projects = [];

        GuaranteeProject::with([
            'contract',
            'contract.last_draft',
            'contractor',
            'project',
            'project.homeowner'
        ])
            ->withCount('drafts')
            ->withCount('issues')
            ->orderBy('id', 'desc')
            ->get()
            ->map(function ($guarantee_project) use (&$projects, $query_search, $state_search) {
                // Filter projects with no scope yet (no drafts)
                if (!$guarantee_project->project || !$guarantee_project->drafts_count) {
                    return false;
                }

                $project_name = $guarantee_project->project['project_name'];
                $homeowner = $guarantee_project->project->homeowner;
                $contractor = $guarantee_project->contractor;

                // Filter by seach query string
                if (($query_search && (
                    compareStrings($query_search, $project_name) ||
                    compareStrings($query_search, $homeowner['firstname']) ||
                    compareStrings($query_search, $homeowner['lastname']) ||
                    compareStrings($query_search, $contractor['firstname']) ||
                    compareStrings($query_search, $contractor['lastname']))
                    ) || !$query_search) {

                    // Get project status by milestone statuses
                    if ( Milestone::where('contract_draft_id', '=', $guarantee_project->contract->last_draft['id'])
                            ->where('status', '=', Milestone::STATUS_COMPLETED)
                            ->exists()
                        && !Milestone::where('contract_draft_id', '=', $guarantee_project->contract->last_draft['id'])
                            ->where('status', '!=', Milestone::STATUS_COMPLETED)
                            ->exists() ) {
                        $project_status = Milestone::STATUS_COMPLETED;
                    } else {
                        $project_status = Milestone::STATUS_IN_PROGRESS;
                    }

                    // Filter by project status
                    if (!$state_search || $state_search == 'all' || $project_status == $state_search) {
                        $projects[] = [
                            'guarantee_project_id' => $guarantee_project['id'],
                            'project_name' => $project_name,
                            'homeowner' => $homeowner,
                            'title_pro' => trans('contract.contract_pro', [
                                'guarantee_project_id' => $guarantee_project['id'],
                                'representative_name' => $guarantee_project->contract->interlocutor_info['representative_name'],
                                'company_name' => $guarantee_project->contract->interlocutor_info['company_name']
                            ]),
                            'title_ho' => trans('contract.contract_ho', [
                                'homeowner_name' => $homeowner['firstname'] . ' ' . $homeowner['lastname']
                            ]),
                            'status' => $project_status,
                            'issues_count' => $guarantee_project->issues_count,
                            'price_discrepancy' => $guarantee_project->price_discrepancy()->exists()
                        ];
                    }

                }
            })
            ->reject(function ($value) {
                return $value === false;
            });

        return [
            'status_code' => 200,
            'response' => [
                'projects' => $projects
            ]
        ];
    }
}

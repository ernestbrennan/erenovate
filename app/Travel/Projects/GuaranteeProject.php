<?php

namespace App\Travel\Projects;

use Illuminate\Database\Eloquent\Model;
use App\Repositories\ContractRepository;
use App\Travel\Chats\Chat;
use App\Travel\Contracts\Contract;
use App\Travel\ContractsDraft\ContractDraft;
use App\Travel\Issues\Issue;
use App\Travel\Messages\Message;
use App\Travel\Notes\Note;
use App\Travel\Users\User;

class GuaranteeProject extends Model
{
    protected $table = 'guarantee_project';

    const STATUS_ACTIVE = 'active';
    const STATUS_WITHDRAW = 'withdraw';

    const NOT_VISITED = 0;
    const VISITED = 1;

    protected $fillable = [
        'contractor_id', 'homeowner_id', 'chat_id', 'project_id', 'status', 'contractor_visited', 'homeowner_visited'
    ];

    protected $appends = ['project_link'];

    public function getProjectLinkAttribute()
    {
        $project_link = env('WWW_MAIN_APP_URL') . '/homeowners/project-details/' . base64_encode($this->project_id);

        if (\Auth::user()['role'] === User::ROLE_CONTRACTOR) {

            $project_link = env('MAIN_APP_URL') . '/contractors/LeadDetails/projectDetails/' . $this->project_id;
        }

        return $project_link;
    }

    public function contractor()
    {
        return $this->belongsTo(User::class, 'contractor_id');
    }

    public function homeowner()
    {
        return $this->belongsTo(User::class, 'homeowner_id');
    }

    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }

    public function project()
    {
        return $this->hasOne(ErenovateProject::class, 'id', 'project_id');
    }

    public function contract()
    {
        return $this->hasOne(Contract::class);
    }

    public function drafts()
    {
        return $this->hasManyThrough(ContractDraft::class, Contract::class);
    }

    public function messages()
    {
        return $this->hasManyThrough(Message::class, Chat::class);
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    public function issues()
    {
        return $this->hasMany(Issue::class);
    }

    public function price_discrepancy()
    {
        return $this->hasOne(Issue::class)
            ->where('type', Issue::TYPE_PRICE_MODIFICATION)
            ->where('status',  '!=', Issue::STATUS_CLOSED);
    }

    public function getData()
    {
        $contract_status = $this->contract['status'];

        return [
            'id' => $this->id,
            'project_link' => $this['project_link'],
            'has_contract_draft' => !empty($this->contract->last_draft) && $this->contract->last_draft['status'] != ContractDraft::STATUS_REJECTED,
            'contract_status' => $this->contract['status'],
            'price_discrepancy' => \Auth::user()['role'] === User::ROLE_HOMEOWNER ? $this->price_discrepancy : null,
            'project_name' => $this->project['project_name'],
            'project_description' => $this->project['comments'],
            'pay_fee' => $contract_status  === Contract::STATUS_PENDING && $this->contract->last_draft['current_accepted'] && $this->contract->last_draft['interlocutor_accepted']
        ];
    }

    public static function getOrCreate($project_id, $contractor_id)
    {
        $guarantee_project = GuaranteeProject::query()
            ->where('contractor_id', $contractor_id)
            ->where('project_id', $project_id)
            ->where('status', GuaranteeProject::STATUS_ACTIVE);

        if (!$guarantee_project->exists()) {

            $project = ErenovateProject::query()->find($project_id);

            $chat = Chat::query()->create();

            $guarantee_project = GuaranteeProject::query()->create([
                'project_id' => $project_id,
                'contractor_id' => $contractor_id,
                'homeowner_id' => $project->user_id,
                'chat_id' => $chat->id,
                'status' => GuaranteeProject::STATUS_ACTIVE,
                'contractor_visited' => GuaranteeProject::NOT_VISITED,
                'homeowner_visited' => GuaranteeProject::NOT_VISITED
            ]);

            ContractRepository::createWithRelations($guarantee_project['id']);

        } else {

            $guarantee_project = $guarantee_project->first();
        }

        return $guarantee_project;
    }
}

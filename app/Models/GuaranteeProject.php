<?php

namespace App\Models;

use App\Exceptions\GuaranteeProjectInvalidAccessException;
use App\Repositories\ContractRepository;
use Illuminate\Database\Eloquent\Model;

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

    public function chat_room()
    {
        return $this->belongsTo(ChatRoom::class);
    }

    public function project()
    {
        return $this->hasOne(Project::class, 'id', 'project_id');
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
        return $this->hasManyThrough(Message::class, ChatRoom::class);
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
            'pay_fee' => $contract_status  === Contract::STATUS_PENDING && $this->contract->last_draft['current_accepted'] && $this->contract->last_draft['interlocutor_accepted']
        ];
    }
    
    public function delete()
    {
        if ($this->contract->drafts) {
            $this->contract->drafts->each->delete();
        }

        $this->contract->delete();
        $this->chat_room->delete();

        parent::delete();
    }

    public static function getOrCreate($project_id, $contractor_id)
    {
        $guarantee_project = GuaranteeProject::query()
            ->where('contractor_id', $contractor_id)
            ->where('project_id', $project_id)
            ->where('status', GuaranteeProject::STATUS_ACTIVE);

        if (!$guarantee_project->exists()) {

            $project = Project::query()->find($project_id);

            $chat_room = ChatRoom::query()->create();

            $guarantee_project = GuaranteeProject::query()->create([
                'project_id' => $project_id,
                'contractor_id' => $contractor_id,
                'homeowner_id' => $project->user_id,
                'chat_id' => $chat_room['id'],
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

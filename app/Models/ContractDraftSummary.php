<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContractDraftSummary extends Model
{
    protected $table = 'guarantee_contract_draft_summary';

    protected $fillable = [
       'contract_draft_id', 'start_date', 'end_date', 'service_cost', 'material_cost',  'changed_total'
    ];

    public function setServiceCostAttribute($value)
    {
        $this->attributes['service_cost'] = str_replace(',', '', $value);
    }

    public function getServiceCostAttribute($value)
    {
        return number_format($value, 2, '.', ',');
    }

    public function setMaterialCostAttribute($value)
    {
        $this->attributes['material_cost'] = str_replace(',', '', $value);
    }

    public function getMaterialCostAttribute($value)
    {
        return number_format($value, 2, '.', ',');
    }

    public function setChangedTotalAttribute($value)
    {
        $this->attributes['changed_total'] = str_replace(',', '', $value);
    }

    public function getChangedTotalAttribute($value)
    {
        return number_format($value, 2, '.', ',');
    }

    public function contract_draft()
    {
        return $this->belongsTo(ContractDraft::class);
    }
}


<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContractSigned extends Model
{
    protected $table = 'guarantee_contract_signed';
    protected $fillable = [
        'contract_draft_id', 'file_id', 'description'
    ];

    public function contract_draft()
    {
        return $this->belongsTo(ContractDraft::class);
    }

    public function file()
    {
        return $this->hasOne(File::class, 'id', 'file_id');
    }
}

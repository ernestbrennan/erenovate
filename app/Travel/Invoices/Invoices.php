<?php

namespace App\Travel\Invoices;

use App\Travel\Materials\Material;
use Illuminate\Database\Eloquent\Model;
use App\Travel\Batches\Batch;
use App\Travel\Files\File;
use App\Travel\Milestones\Milestone;
use App\Travel\Works\Work;

class Invoice extends Model
{
    protected $table = 'guarantee_invoice';

    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_UNVERIFIED= 'unverified';
    const STATUS_COMPLETED= 'completed';
    const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'milestone_id', 'title', 'description', 'number', 'taxes', 'total_price', 'due_date', 'creation_date','status',
    ];
    public function setTaxesAttribute($value)
    {
        $this->attributes['taxes'] = str_replace(',', '', $value);
    }

    public function getTaxesAttribute($value)
    {
        return number_format($value, 2, '.', ',');
    }

    public function milestone()
    {
        return $this->belongsTo(Milestone::class);
    }

    public function batches()
    {
        return $this->belongsToMany(Batch::class,
            'guarantee_invoice_batch',
            'invoice_id',
            'batch_id'
        );
    }

    public function works()
    {
        return $this->hasMany(Work::class, 'invoice_id', 'id');
    }

    public function materials()
    {
        return $this->belongsToMany(
            Material::class,
            'guarantee_invoice_material',
            'invoice_id',
            'material_id'
        );
    }

    public function material_files()
    {
        return $this->belongsToMany(
            File::class,
            'guarantee_invoice_material_file',
            'invoice_id',
            'file_id');
    }
}

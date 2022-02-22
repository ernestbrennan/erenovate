<?php

namespace App\Models;

use App\Travel\SystemNotifications\AbstractSystemNotification;
use App\Travel\SystemNotifications\SystemNotification;
use Illuminate\Database\Eloquent\Model;

class MaterialsContent extends Model
{
    protected $table = 'guarantee_materials_content';

    const STATUS_PENDING = 'pending';
    const STATUS_ACTIVE = 'active';
    const STATUS_REJECTED = 'rejected';

    const DEFAULT_VERSION = 1;

    protected $fillable = [
        'milestone_content_id', 'version', 'status'
    ];

    public function milestone_content()
    {
        return $this->belongsTo(MilestoneContent::class);
    }

    public function materials()
    {
        return $this->belongsToMany(
            Material::class,
            'guarantee_materials_content_material',
            'materials_content_id',
            'material_id'
        );
    }

    public function material_files()
    {
        return $this->belongsToMany(
            File::class,
            'guarantee_materials_content_material_file',
            'materials_content_id',
            'file_id');
    }

    public function edited_notification()
    {
        return $this->hasOne(SystemNotification::class)
            ->where('type', AbstractSystemNotification::TYPE_MATERIALS_CONTENT_EDITED)
            ->orderBy('id', 'desc');
    }
}

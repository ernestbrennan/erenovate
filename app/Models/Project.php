<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Support\Collection;

class Project extends Model
{
    protected $table = 'tbl_project';

    protected $fillable = [
        'user_id',
    ];

    public function homeowner()
    {
        return $this->belongsTo(User::class, 'user_id', 'userId');
    }

    public static function create(Collection $data)
    {
        $user_id = $data->get('user_id');
        $project_name = $data->get('project_name', '');

        return DB::table('tbl_project')->insertGetId([
            'user_id' => $user_id,
            'project_name' => $project_name,
            'project_closed' => '1',

            'location' => '',

            'dateadded' => 0,
            'project_id' => 0,
            'project_category' => 0,
            'latitude' => 0,
            'longitude' => 0,
        ]);
    }
}

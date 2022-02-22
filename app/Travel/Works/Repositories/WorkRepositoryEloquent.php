<?php

namespace App\Travel\Works\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use App\Travel\Works\Repositories\Interfaces\WorkRepositoryInterface;
use App\Travel\Works\Work;

class WorkRepositoryEloquent extends BaseRepository implements WorkRepositoryInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string
    {
        return Work::class;
    }
}
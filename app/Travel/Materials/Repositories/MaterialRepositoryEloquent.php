<?php

namespace App\Travel\Materials\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use App\Travel\Materials\Repositories\Interfaces\MaterialRepositoryInterface;
use App\Travel\Materials\Material;

class MaterialRepositoryEloquent extends BaseRepository implements MaterialRepositoryInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string
    {
        return Material::class;
    }
}
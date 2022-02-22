<?php

namespace App\Travel\Files\Repositories;

use App\Travel\Files\File;
use App\Travel\Files\Repositories\Interfaces\FileRepositoryInterface;
use Prettus\Repository\Eloquent\BaseRepository;

class FileRepositoryEloquent extends BaseRepository implements FileRepositoryInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string
    {
        return File::class;
    }
}
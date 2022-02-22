<?php

namespace App\Travel\ContractsDraft\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use App\Travel\CotnractsDraft\Repositories\Interfaces\ContractDraftRepositoryInterface;
use App\Travel\ContractsDraft\ContractDraft;
use App\Travel\ContractsDraft\Exceptions\{
    NotFoundContractDraftErrorException
};

class ContractDraftRepositoryEloquent extends BaseRepository implements ContractDraftRepositoryInterface
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
    ];

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string
    {
        return ContractDraft::class;
    }
}
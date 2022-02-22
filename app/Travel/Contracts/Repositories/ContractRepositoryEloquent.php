<?php

namespace App\Travel\Contracts\Repositories;

use App\Travel\Contracts\Contract;
use App\Travel\Contracts\Repositories\Interfaces\ContractRepositoryInterface;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Travel\Contracts\Exceptions\{CreateContractErrorException, NotFoundContractErrorException};

class ContractRepositoryEloquent extends BaseRepository implements ContractRepositoryInterface
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
        return Contract::class;
    }

    /**
     * @param array $data
     * @return Contract
     * @throws CreateContractErrorException
     */
    public function createContract(array $data = []): Contract
    {
        try {
            return $this->create(array_merge($data, [
                'status' => Contract::STATUS_PENDING,
            ]));

        } catch (\Exception $e) {
            throw new CreateContractErrorException($e);
        }
    }


}
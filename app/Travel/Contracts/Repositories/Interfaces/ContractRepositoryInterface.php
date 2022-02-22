<?php

namespace App\Travel\Contracts\Repositories\Interfaces;

use App\Travel\Contracts\Contract;
use Prettus\Repository\Contracts\RepositoryInterface;
use App\Travel\Contracts\Exceptions\{CreateContractErrorException, NotFoundContractErrorException};

interface ContractRepositoryInterface extends RepositoryInterface
{
    /**
     * @param array $data
     * @return Contract
     * @throws CreateContractErrorException
     */
    public function createContract(array $data = []) :Contract;

}
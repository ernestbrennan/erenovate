<?php

namespace App\Travel\Users\Repositories\Interfaces;

use App\Travel\Users\User;
use Prettus\Repository\Contracts\RepositoryInterface;
use App\Travel\Users\Exceptions\{
    NotFoundUserErrorException,
};

interface UserRepositoryInterface extends RepositoryInterface
{
    /**
     * @param User $user
     * @return array
     */
    public function getForUserInfo($user) : array;

    /**
     * @param array $data
     * @return User
     */
    public function createUser($data): User;
}
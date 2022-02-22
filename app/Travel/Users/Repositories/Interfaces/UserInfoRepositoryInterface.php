<?php

namespace App\Travel\Users\Repositories\Interfaces;

use App\Travel\Users\UserInfo;
use Prettus\Repository\Contracts\RepositoryInterface;
use App\Travel\Users\Exceptions\{CreateUserInfoErrorException, NotFoundUserErrorException};

interface UserInfoRepositoryInterface extends RepositoryInterface
{
    /**
     * @param array $data
     * @return UserInfo
     * @throws CreateUserInfoErrorException
     */
    public function createUserInfo(array $data = []) :UserInfo;
}
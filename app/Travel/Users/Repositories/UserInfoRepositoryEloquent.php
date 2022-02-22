<?php

namespace App\Travel\Users\Repositories;

use App\Travel\Users\Exceptions\CreateUserInfoErrorException;
use App\Travel\Users\Repositories\Interfaces\UserInfoRepositoryInterface;
use App\Travel\Users\UserInfo;
use Prettus\Repository\Eloquent\BaseRepository;


class UserInfoRepositoryEloquent extends BaseRepository implements UserInfoRepositoryInterface
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
        return UserInfo::class;
    }

    /**
     * @param array $data
     * @return UserInfo
     * @throws CreateUserInfoErrorException
     */
    public function createUserInfo(array $data = []): UserInfo
    {
        try {
            return $this->create(array_merge($data, [
                'status' => UserInfo::STATUS_NEW
            ]));

        } catch (\Exception $e) {
            throw new CreateUserInfoErrorException($e);
        }
    }
}
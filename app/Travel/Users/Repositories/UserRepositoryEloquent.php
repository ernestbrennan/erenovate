<?php

namespace App\Travel\Users\Repositories;

use App\Travel\Users\Exceptions\CreateUserErrorException;
use App\Travel\Users\UserInfo;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Travel\Users\Repositories\Interfaces\UserRepositoryInterface;
use App\Travel\Users\User;


class UserRepositoryEloquent extends BaseRepository implements UserRepositoryInterface
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
        return User::class;
    }

    /**
     * @param User $user
     * @return array
     */
    public function getForUserInfo($user): array
    {
        if ($user->role === User::ROLE_HOMEOWNER) {
            $data = ['type' => UserInfo::TYPE_INDIVIDUAL, 'postal_code' => $user->postalcode,];
        }

        if ($user->role === User::ROLE_CONTRACTOR) {

            $business_profile = $user->user_business_map->business_profile;

            $data = [
                'type' => UserInfo::TYPE_LEGAL,
                'postal_code' => $business_profile['area_postalcode'],
                'company_name' => $business_profile['businessName'],
                'representative_name' => $user->firstname . ' ' . $user->lastname
            ];
        }

        $province = \DB::table('province')->find($user->province_id);

        return array_merge($data, [
            'user_id' => $user->userId,
            'province' => empty($province) ? null : $province->name,
            'first_name' => $user->firstname,
            'last_name' => $user->lastname,
            'city' => $user->city,
            'address_1' => $user->address,
        ]);
    }

    /**
     * @param array $data
     * @return User
     * @throws CreateUserErrorException
     */
    public function createUser($data): User
    {
        try {
            return $this->create($data);
        } catch (\Exception $e) {
            throw new CreateUserErrorException($e);
        }
    }
}
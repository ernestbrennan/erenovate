<?php

namespace App\Travel\ArchivedDrafts\Repositories;

use App\Travel\ArchivedDrafts\ArchivedDraft;
use App\Travel\ArchivedDrafts\Repositories\Interfaces\ArchivedDraftRepositoryInterface;
use Prettus\Repository\Eloquent\BaseRepository;

class ArchivedDraftRepositoryEloquent extends BaseRepository implements ArchivedDraftRepositoryInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string
    {
        return ArchivedDraft::class;
    }

    public function listArchivedDraftsByUser($user, $with = [])
    {
        return $query = ArchivedDraft::with($with)
            ->where('user_id', $user->userId)
            ->orderBy('id', 'desc')
            ->get();
    }
}
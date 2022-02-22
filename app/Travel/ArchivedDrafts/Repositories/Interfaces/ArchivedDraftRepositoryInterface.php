<?php

namespace App\Travel\ArchivedDrafts\Repositories\Interfaces;

use Prettus\Repository\Contracts\RepositoryInterface;

interface ArchivedDraftRepositoryInterface extends RepositoryInterface
{
    public function listArchivedDraftsByUser( $user, $with = []);
}
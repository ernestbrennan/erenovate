<?php

namespace App\Travel\ArchivedDrafts\Services;

use App\Travel\ArchivedDrafts\Repositories\Interfaces\ArchivedDraftRepositoryInterface;
use App\Travel\Base\BaseFormRequest;
use Auth;

class ArchivedDraftService
{
    /**
     * @var ArchivedDraftRepositoryInterface
     */
    private $archived_draft_repository;

    /**
     * ChatService constructor.
     *
     * @param ArchivedDraftRepositoryInterface $archived_draft_repository
     */
    public function __construct(ArchivedDraftRepositoryInterface $archived_draft_repository)
    {
        $this->archived_draft_repository = $archived_draft_repository;
    }


    /**
     * @param BaseFormRequest $request
     * @return mixed
     */
    public function getList($request)
    {
        $user = Auth::user();

        return $user->archived_drafts()
            ->when(
                $request->has('query_search') && !empty($request->get('query_search')),
                function ($query) use ($request) {
                    $query_search = $request->get('query_search');

                    return $query->where('title', 'like', "%$query_search%");
                })
            ->get();
    }

    public function save($request)
    {
        $user = Auth::user();

        $user->archived_drafts()->create([
            'title' => $request->get('title'),
            'draft' => [
                'contract' => $request->get('contract'),
                'contract_draft' => $request->get('contract_draft')
            ]
        ]);
    }

    public function update($request)
    {
        Auth::user()->archived_drafts()->find($request->get('id'))->update([
            'draft' => [
                'contract' => $request->get('contract'),
                'contract_draft' => $request->get('contract_draft')
            ]
        ]);
    }

    public function delete($request)
    {
        return Auth::user()->archived_drafts()->find($request->get('id'))->delete();
    }


    public function getById($request)
    {
        $user = Auth::user();

        return $user->archived_drafts()->find($request->get('id'));
    }


}
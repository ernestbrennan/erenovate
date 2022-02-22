<?php

namespace App\Travel\Notes\Services;

use App\Travel\Base\BaseFormRequest;
use App\Travel\Notes\Exceptions\CreateNoteErrorException;
use App\Travel\Notes\Repositories\Interfaces\NoteRepositoryInterface;
use App\Travel\Projects\Repositories\Interfaces\GuaranteeProjectRepositoryInterface;
use Auth;

class NoteService
{
    /**
     * @var NoteRepositoryInterface
     */
    private $note_repository;

    /**
     * @var GuaranteeProjectRepositoryInterface
     */
    private $guarantee_project_repository;

    /**
     * ChatService constructor.
     *
     * @param NoteRepositoryInterface $note_repository
     * @param GuaranteeProjectRepositoryInterface $guarantee_project_repository
     */
    public function __construct(NoteRepositoryInterface $note_repository,
                                GuaranteeProjectRepositoryInterface $guarantee_project_repository)
    {
        $this->note_repository = $note_repository;
        $this->guarantee_project_repository = $guarantee_project_repository;
    }

    /**
     * @param BaseFormRequest $request
     * @return mixed
     * @throws \App\Travel\Projects\Exceptions\NotFoundGuaranteeProjectErrorException
     */
    public function getByProject($request)
    {
        $guarantee_project_id = $request->input('guarantee_project_id');
        $data = [
            'guarantee_project_id' =>  $guarantee_project_id,
            'user_id' => Auth::id()
        ];

        $notes = $this->note_repository->getNotesByParams($data, ['files']);

        $guarantee_project = $this->guarantee_project_repository->findGuaranteeProjectById($guarantee_project_id);
        $guarantee_project = $this->guarantee_project_repository->getBaseData($guarantee_project);

        return compact('notes', 'guarantee_project');
    }

    /**
     * @param BaseFormRequest $request
     * @return mixed
     * @throws CreateNoteErrorException
     */
    public function sendNote($request)
    {
        $note = $this->note_repository->createNote([
            'user_id' => Auth::id(),
            'guarantee_project_id' => $request->input('guarantee_project_id'),
            'content' => $request->input('content'),
        ]);

        $this->note_repository->attachFiles($note, $request->input('files', []));

        $note->load('files');

        return compact('note');
    }
}
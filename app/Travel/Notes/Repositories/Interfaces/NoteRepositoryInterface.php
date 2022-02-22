<?php

namespace App\Travel\Notes\Repositories\Interfaces;

use App\Travel\Notes\Exceptions\CreateNoteErrorException;
use App\Travel\Notes\Note;
use Prettus\Repository\Contracts\RepositoryInterface;

interface NoteRepositoryInterface extends RepositoryInterface
{
    /**
     * @param array $data
     * @param array $with
     * @return Note []
     */
    public function getNotesByParams($data, $with);

    /**
     * @param array $data
     * @return Note
     * @throws CreateNoteErrorException
     */
    public function createNote(array $data): Note;

    /**
     * @param array $files
     * @param Note $model
     */
    public function attachFiles(Note $model, $files): void;
}
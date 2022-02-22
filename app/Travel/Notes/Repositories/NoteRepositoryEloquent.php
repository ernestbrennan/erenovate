<?php

namespace App\Travel\Notes\Repositories;

use App\Travel\Notes\Exceptions\CreateNoteErrorException;
use Illuminate\Support\Collection;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Travel\Notes\Repositories\Interfaces\NoteRepositoryInterface;
use App\Travel\Notes\Note;

class NoteRepositoryEloquent extends BaseRepository implements NoteRepositoryInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string
    {
        return Note::class;
    }

    /**
     * @param array $data
     * @param array $with
     * @return Note []
     */
    public function getNotesByParams($data, $with)
    {
        return Note::with($with)
            ->where('guarantee_project_id', $data['guarantee_project_id'])
            ->where('user_id', $data['user_id'])
            ->orderBy('id', 'desc')
            ->get();
    }

    /**
     * @param array $data
     * @return Note
     * @throws CreateNoteErrorException
     */
    public function createNote(array $data): Note
    {
        try {
            return $this->create($data);
        } catch (\Exception $e) {
            throw new CreateNoteErrorException($e);
        }    }

    /**
     * @param array $files
     * @param Note $model
     */
    public function attachFiles(Note $model, $files): void
    {
        foreach ($files as $file) {
            $model->files()->attach($file['id']);
        }
    }
}
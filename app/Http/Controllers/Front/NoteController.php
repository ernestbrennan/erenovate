<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\Note as R;
use App\Http\Requests\Note\SendNoteRequest;
use App\Travel\Notes\Exceptions\CreateNoteErrorException;
use App\Travel\Notes\Requests\GetListRequest;
use App\Travel\Notes\Requests\SendRequest;
use App\Travel\Notes\Services\NoteService;
use App\Travel\Projects\Exceptions\NotFoundGuaranteeProjectErrorException;
use App\Travel\Projects\GuaranteeProject;
use App\Travel\Notes\Note;
use Auth;

class NoteController extends Controller
{
    public $service;

    public function __construct(NoteService $service)
    {
        $this->service = $service;
    }

    public function getList(GetListRequest $request)
    {
        try {
            return response()->json($this->service->getByProject($request), 200);

        } catch (NotFoundGuaranteeProjectErrorException $e) {
            return response()->json([], 422);
        }
    }

    public function sendNote(SendRequest $request)
    {
        try {
            return response()->json($this->service->sendNote($request), 200);

        } catch (CreateNoteErrorException $e) {
            return response()->json([], 422);
        }
    }
}
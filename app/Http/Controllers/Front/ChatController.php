<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Travel\Chats\Requests\GetByProjectRequest;
use App\Travel\Chats\Services\ChatService;
use App\Travel\Projects\Exceptions\NotFoundGuaranteeProjectErrorException;

class ChatController extends Controller
{
    public $service;

    public function __construct(ChatService $service)
    {
        $this->service = $service;
    }

    public function getByProject(GetByProjectRequest $request)
    {
        try {
            return response()->json($this->service->getByProject($request), 200);

        } catch (NotFoundGuaranteeProjectErrorException $e) {
            return response()->json([], 422);
        }
    }
}
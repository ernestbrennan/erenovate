<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Travel\Chats\Exceptions\CreateChatErrorException;
use App\Travel\Issues\Exceptions\{
    CreateIssueErrorException, NotFoundIssueErrorException, UpdateIssueErrorException
};
use App\Travel\Issues\Requests\{
    CloseRequest, CreateRequest, GetByIdRequest, SendMessageRequest
};
use App\Travel\Issues\Services\IssueService;
use App\Travel\Messages\Exceptions\CreateMessageErrorException;
use App\Travel\Projects\Exceptions\NotFoundGuaranteeProjectErrorException;

class IssueController extends Controller
{
    public $service;

    public function __construct(IssueService $service)
    {
        $this->service = $service;
    }

    public function create(CreateRequest $request)
    {
        try {
            return response()->json($this->service->createIssue($request), 200);

        } catch (CreateIssueErrorException | CreateChatErrorException $e) {
            return response()->json(422);
        }
    }

    public function close(CloseRequest $request)
    {
        try {
            return response()->json($this->service->closeIssue($request), 200);

        } catch (UpdateIssueErrorException | NotFoundIssueErrorException $e) {
            return response()->json(422);
        }
    }

    public function getById(GetByIdRequest $request)
    {
        try {
            return response()->json($this->service->getById($request), 200);

        } catch (NotFoundIssueErrorException | NotFoundGuaranteeProjectErrorException$e) {
            return response()->json(422);
        }
    }

    public function sendMessage(SendMessageRequest $request)
    {
        try {
            return response()->json($this->service->sendMessage($request), 200);

        } catch (NotFoundIssueErrorException | CreateMessageErrorException $e) {
            return response()->json(422);
        }
    }
}

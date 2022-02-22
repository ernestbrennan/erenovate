<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Travel\Issues\Requests\{CloseRequest, GetByIdRequest, SendMessageRequest, GetByProjectRequest};
use App\Travel\Issues\Services\AdminIssueService;
use App\Travel\Issues\Exceptions\UpdateIssueErrorException;
use App\Travel\Issues\Exceptions\NotFoundIssueErrorException;
use App\Travel\Messages\Exceptions\CreateMessageErrorException;
use App\Travel\Projects\Exceptions\NotFoundGuaranteeProjectErrorException;

class IssueController extends Controller
{
    public $service;

    public function __construct(AdminIssueService $service)
    {
        $this->service = $service;
    }

    public function getByProject(GetByProjectRequest $request)
    {
        try {
            return response()->json($this->service->getByProject($request), 200);

        } catch (NotFoundGuaranteeProjectErrorException $e) {
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

        } catch (NotFoundIssueErrorException | CreateMessageErrorException$e) {
            return response()->json(422);
        }
    }


    public function close(CloseRequest $request)
    {

        try {
            return response()->json($this->service->closeIssue($request), 200);

        } catch (NotFoundIssueErrorException | UpdateIssueErrorException$e) {
            return response()->json(422);
        }
    }
}

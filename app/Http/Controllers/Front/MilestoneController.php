<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\Milestone as R;
use App\Travel\Milestones\Requests\GetCurrentRequest;
use App\Travel\Milestones\Services\MilestoneService;
use Illuminate\Http\Response;

class MilestoneController extends Controller
{
    public $service;

    public function __construct(MilestoneService $service)
    {
        $this->service = $service;
    }

    public function getCurrent(GetCurrentRequest $request)
    {
        return response()->json([
            'response' => $this->service->getCurrent($request),
            'status_code' => 200
        ], Response::HTTP_OK);
    }

    public function getById(R\GetByIdRequest $request)
    {
        return response()->json([
            'response' => $this->service->getById($request),
            'status_code' => 200
        ], Response::HTTP_OK);
    }

    public function getEdited(R\GetCurrentRequest $request)
    {
        return response()->json([
            'response' => $this->service->getEdited($request),
            'status_code' => 200
        ], Response::HTTP_OK);
    }

    public function edit(R\EditRequest $request)
    {
        $this->service->edit($request);

        return response()->json([
            'status_code' => 200
        ], Response::HTTP_OK);
    }

    public function accept(R\AcceptRequest $request)
    {
        $this->service->accept($request);

        return response()->json([
            'status_code' => 200
        ], Response::HTTP_OK);
    }

    public function reject(R\RejectRequest $request)
    {
        $this->service->reject($request);

        return response()->json([
            'status_code' => 200
        ], Response::HTTP_OK);
    }
}

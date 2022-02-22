<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\Project as R;
use App\Travel\Projects\Exceptions\CreateGuaranteeProjectErrorException;
use App\Travel\Projects\Exceptions\DeleteGuaranteeProjectErrorException;
use App\Travel\Projects\Exceptions\NotFoundGuaranteeProjectErrorException;
use App\Travel\Projects\Exceptions\UpdateGuaranteeProjectErrorException;
use App\Travel\Projects\Requests\HireFromErenovateRequest;
use App\Travel\Projects\Requests\GetOrCreateRequest;
use App\Travel\Projects\Requests\VisitedRequest;
use App\Travel\Projects\Requests\VisitRequest;
use App\Travel\Projects\Requests\WithdrawRequest;
use App\Travel\Projects\Services\ProjectService;

class ProjectController extends Controller
{
    public $service;

    public function __construct(ProjectService $service)
    {
        $this->service = $service;
    }

    public function getOrCreate(GetOrCreateRequest $request)
    {
        try {
            return response()->json($this->service->getOrCreate($request), 200);

        } catch (CreateGuaranteeProjectErrorException $e) {
            return response()->json([], 422);
        }
    }

    public function hireFromErenovate(HireFromErenovateRequest $request)
    {
        try {
            return response()->json($this->service->hireFromErenovate($request), 200);

        } catch (CreateGuaranteeProjectErrorException $e) {
            return response()->json([], 422);
        }
    }

    public function withdraw(WithdrawRequest $request)
    {
        try {
            return response()->json($this->service->withdraw($request), 200);

        } catch (\Exception | DeleteGuaranteeProjectErrorException $e) {
            return response()->json([$e->getMessage()], 422);
        }
    }

    public function inviteHO(R\InviteHORequest $request)
    {
        try {
            return response()->json($this->service->inviteHo($request), 200);

        } catch (\Exception $e) {
            return response()->json([$e->getMessage()], 422);
        }
    }

    public function visit(VisitRequest $request)
    {
        try {
            return response()->json($this->service->userVisit($request) , 200);

        } catch (UpdateGuaranteeProjectErrorException $e) {
            return response()->json(['errors' => $e->getMessage()], 422);
        }
    }

    public function checkVisited(VisitedRequest $request)
    {
        try {
            return response()->json(['visited' => $this->service->checkVisited($request)] , 200);

        } catch (NotFoundGuaranteeProjectErrorException $e) {
            return response()->json(['errors' => $e->getMessage()], 422);
        }
    }
}

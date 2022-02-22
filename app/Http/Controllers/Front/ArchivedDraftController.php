<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Travel\ArchivedDrafts\Requests as R;
use App\Travel\ArchivedDrafts\Services\ArchivedDraftService;
use Illuminate\Http\Response;

class ArchivedDraftController extends Controller
{
    public $service;

    public function __construct(ArchivedDraftService $service)
    {
        $this->service = $service;
    }

    public function getList(R\GetListRequest $request)
    {
        return response()->json([
            'response' => $this->service->getList($request),
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

    public function save(R\SaveRequest $request)
    {
        $this->service->save($request);

        return response()->json([
            'status_code' => 200
        ], Response::HTTP_OK);
    }

    public function update(R\UpdateRequest $request)
    {
        $this->service->update($request);

        return response()->json([
            'status_code' => 200
        ], Response::HTTP_OK);
    }

    public function delete(R\DeleteRequest $request)
    {
        $this->service->delete($request);

        return response()->json([
            'status_code' => 200
        ], Response::HTTP_OK);
    }
}

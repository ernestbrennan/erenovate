<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Travel\Summary\Requests as R;
use App\Travel\Summary\Services\SummaryService;
use Illuminate\Http\Response;

class SummaryController extends Controller
{
    private $service;

    public function __construct(SummaryService $service)
    {
        $this->service = $service;
    }

    public function byProject(R\ByProjectRequest $request)
    {
        return response()->json([
            'response' => $this->service->byProject($request),
            'status_code' => 200
        ], Response::HTTP_OK);
    }
}

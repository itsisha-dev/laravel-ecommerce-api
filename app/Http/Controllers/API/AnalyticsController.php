<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AnalyticsService;
use App\Http\Responses\ApiResponse;

class AnalyticsController extends Controller
{

    public function __construct(protected AnalyticsService $service) {}

    public function topProducts(Request $request)
    {
        $jwtToken = $request->bearerToken();
        $params = $request->only(['limit','category_id']);
        $data = $this->service->topProducts($params, $jwtToken);
        return ApiResponse::success($data);
    }

    public function basicStats(Request $request)
    {
        $jwtToken = $request->bearerToken();
        $data = $this->service->basicStats($jwtToken);
        return ApiResponse::success($data);
    }
}
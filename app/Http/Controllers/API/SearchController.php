<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\SearchService;
use App\Http\Responses\ApiResponse;

class SearchController extends Controller
{
    public function __construct(protected SearchService $searchService) {}

    public function index(Request $request)
    {
        $jwtToken = $request->bearerToken();

        // $user = $request->user(); // optional, only if user is logged in
        // if (!$user) {
        //     return response()->json(['error' => 'Unauthorized'], 401);
        // }

        // $jwtToken = JWTAuth::fromUser($user);

        $params = [
            'q' => $request->query('q'),
            'category_id' => $request->query('category_id'),
            'page' => $request->query('page', 1),
            'per_page' => $request->query('per_page', 10),
        ];

        $data = $this->searchService->search($params, $jwtToken);

        return ApiResponse::success($data);
    }
}
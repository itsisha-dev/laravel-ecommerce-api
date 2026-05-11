<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ChatbotService;
use App\Http\Responses\ApiResponse;

class ChatbotController extends Controller
{

    public function __construct(protected ChatbotService $chatbot) {}

    public function send(Request $request)
    {
        $jwtToken = $request->bearerToken();
        $request->validate(['message' => 'required|string']);
        $response = $this->chatbot->sendMessage($request->message, $jwtToken);
        return ApiResponse::success(['response' => $response]);
    }
}
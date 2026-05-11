<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ChatbotService
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.fastapi.url');
    }

    public function sendMessage(string $message, $jwtToken)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $jwtToken,
                'Accept' => 'application/json'
            ])->timeout(5)->retry(2, 100)
            ->post($this->baseUrl . '/chat', ['message' => $message])
            ->throw();

            return $response->json()['response'] ?? "Sorry, I couldn't process that.";

        } catch (\Exception $e) {

            \Log::error($e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            $message = $e->getMessage();

            if (preg_match('/\{.*\}/', $message, $matches)) {
                $json = json_decode($matches[0], true);
                // $detail = $json['detail'] ?? null;
                // return ['data'=>[], 'error'=>$detail];
                $detail = $json['meta'] ?? null;
                return $detail;
            }

            return ['data'=>[], 'error'=>'Service unavailable.'];
        }
    }
}
<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SearchService
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.fastapi.url');
    }

    public function search($params, $jwtToken)
    {
        $cacheKey = 'search:' . md5(json_encode($params));

        return cache()->remember($cacheKey, 60, function() use ($params, $jwtToken) {

            try {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $jwtToken,
                    'Accept' => 'application/json'
                ])
                ->timeout(5)        // Fail if server doesn't respond in 3 seconds
                ->retry(2, 100)     // Retry 2 times with 100ms pause if request fails
                ->get($this->baseUrl . '/search', $params);

                $response->throw();   // throws if status != 200

                return $response->json();

            } catch (\Exception $e) {

                \Log::error('FastAPI search failed', [
                    'params' => $params,
                    'error' => $e->getMessage(),
                ]);

                $response = $e->response; 
                $data = $response ? $response->json() : [];
                $errorMessage = $data['detail'] ?? 'Search service unavailable';

                return [
                    'data' => [],
                    'meta' => ['error' => $errorMessage]
                ];
            }
        });
    }
}
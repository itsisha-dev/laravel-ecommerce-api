<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class AnalyticsService
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.fastapi.url');
    }

    public function topProducts(array $params, $jwtToken)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $jwtToken,
                'Accept' => 'application/json'
            ])->timeout(5)->retry(2, 100)
            ->get($this->baseUrl . '/analytics/top-products', $params);

            $response->throw();

            return $response->json();

        } catch (\Exception $e) {

           // Log::error('FastAPI top-products failed', ['error'=>$e->getMessage(),'params'=>$params]);
            Log::error($e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return ['data'=>[], 'meta'=>['error'=>'Analytics service unavailable']];
        }
    }

    public function basicStats($jwtToken)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $jwtToken,
                'Accept' => 'application/json'
            ])->timeout(5)->retry(2, 100)
            ->get($this->baseUrl . '/analytics/basic-stats');

            $response->throw();
            return $response->json();

        } catch (\Exception $e) {
            Log::error('FastAPI basic-stats failed', ['error'=>$e->getMessage()]);
            return ['data'=>[], 'meta'=>['error'=>'Analytics service unavailable']];
        }
    }
}
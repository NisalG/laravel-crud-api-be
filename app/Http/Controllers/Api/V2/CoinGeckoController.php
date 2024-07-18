<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Services\CoinGeckoService;
use Illuminate\Http\JsonResponse;

class CoinGeckoController extends Controller
{
    protected $coinGeckoService;

    public function __construct(CoinGeckoService $coinGeckoService)
    {
        $this->coinGeckoService = $coinGeckoService;
    }

    public function getCryptocurrencyData($id): JsonResponse
    {
        $data = $this->coinGeckoService->getCryptocurrencyData($id);
        return response()->json($data);
    }

    public function getMarketData(): JsonResponse
    {
        $data = $this->coinGeckoService->getMarketData();
        return response()->json($data);
    }
}

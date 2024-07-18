<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CoinGeckoService
{
    protected $baseUri;

    public function __construct()
    {
        $this->baseUri = 'https://api.coingecko.com/api/v3/';
    }

    public function getCryptocurrencyData($id)
    {
        $response = Http::get($this->baseUri . 'coins/' . $id);
        return $response->json();
    }

    public function getMarketData()
    {
        $response = Http::get($this->baseUri . 'coins/markets', [
            'vs_currency' => 'usd',
            'order' => 'market_cap_desc',
            'per_page' => 10,
            'page' => 1,
            'sparkline' => false,
        ]);

        return $response->json();
    }
}

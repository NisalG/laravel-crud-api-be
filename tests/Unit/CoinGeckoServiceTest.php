<?php

namespace Tests\Unit;

use App\Services\CoinGeckoService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CoinGeckoServiceTest extends TestCase
{
    public function testGetCryptocurrencyData()
    {
        Http::fake([
            'https://api.coingecko.com/api/v3/coins/bitcoin' => Http::response([
                'id' => 'bitcoin',
                'symbol' => 'btc',
                'name' => 'Bitcoin',
            ], 200)
        ]);

        $service = new CoinGeckoService();
        $response = $service->getCryptocurrencyData('bitcoin');

        $this->assertIsArray($response);
        $this->assertEquals('bitcoin', $response['id']);
        $this->assertEquals('btc', $response['symbol']);
        $this->assertEquals('Bitcoin', $response['name']);
    }

    public function testGetMarketData()
    {
        Http::fake([
            'https://api.coingecko.com/api/v3/coins/markets*' => Http::response([
                [
                    'id' => 'bitcoin',
                    'symbol' => 'btc',
                    'name' => 'Bitcoin',
                ],
                [
                    'id' => 'ethereum',
                    'symbol' => 'eth',
                    'name' => 'Ethereum',
                ],
            ], 200)
        ]);

        $service = new CoinGeckoService();
        $response = $service->getMarketData();

        $this->assertIsArray($response);
        $this->assertCount(2, $response);
        $this->assertEquals('bitcoin', $response[0]['id']);
        $this->assertEquals('ethereum', $response[1]['id']);
    }
}

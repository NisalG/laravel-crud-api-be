<?php

namespace Tests\Feature;

use App\Services\CoinGeckoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CoinGeckoControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testGetCryptocurrencyData()
    {
        Http::fake([
            'https://api.coingecko.com/api/v3/coins/bitcoin' => Http::response([
                'id' => 'bitcoin',
                'symbol' => 'btc',
                'name' => 'Bitcoin',
            ], 200)
        ]);

        $response = $this->getJson('/api/v2/cryptocurrency/bitcoin');

        $response->assertStatus(200)
                 ->assertJson([
                    'id' => 'bitcoin',
                    'symbol' => 'btc',
                    'name' => 'Bitcoin',
                 ]);
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

        $response = $this->getJson('/api/v2/market-data');

        $response->assertStatus(200)
                 ->assertJson([
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
                 ]);
    }
}

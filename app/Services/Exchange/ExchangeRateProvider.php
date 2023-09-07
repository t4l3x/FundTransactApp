<?php
declare(strict_types=1);

namespace App\Services\Exchange;

use App\Services\CacheService;
use App\ValueObjects\Currency;
use App\ValueObjects\ExchangeRate;
use Illuminate\Support\Facades\Http;
use Money\Money;

class ExchangeRateProvider implements ExchangeRateProviderInterface
{
    private CacheService $cacheService;
    private string $exchangeRateUrl;

    public function __construct(CacheService $cacheService)
    {
        $this->cacheService = $cacheService;
        $this->exchangeRateUrl = 'https://api.exchangerate.host/latest'; // Replace with your actual URL
    }


    public function getExchangeRate(Currency $fromCurrency, Currency $toCurrency): ExchangeRate
    {
        // Check if all exchange rates are cached
        $allRatesCacheKey = 'all_exchange_rates'.$fromCurrency->getCurrency();
        $exchangeRates = $this->cacheService->get($allRatesCacheKey, []);

        // Check if the exchange rate for the specific currency pair is already cached
        $cacheKey = "{$toCurrency->getCurrency()}";

        if (array_key_exists($cacheKey, $exchangeRates)) {
            return $this->cacheService->get($cacheKey);
        }

        // Fetch all exchange rates if not already cached
        if (empty($exchangeRates)) {
            $response = Http::get($this->exchangeRateUrl.'?base='.$fromCurrency->getCurrency());

            if (!$response->successful()) {
                throw new \Exception('Failed to fetch exchange rates from the provider.');
            }

            $data = $response->json();

            // Store all exchange rates in cache
            $allRates = $data['rates'];
            $this->cacheService->put($allRatesCacheKey, $allRates, 1000); // Cache for 60 minutes

            // Convert the rates to Money objects and cache them
            foreach ($allRates as $currencyCode => $rate) {
                $currency = new Currency($currencyCode);
                try {
                    $money = \App\ValueObjects\Money::create($rate, $currency);
                    $exchangeRates[$currencyCode] = new ExchangeRate($fromCurrency, $currency, $money);
                } catch (\Exception $e) {
                    // Handle the exception here, e.g., log it or throw a custom exception
                    // For now, let's just set the rate to 1 for currencies that cannot be converted
                    $exchangeRates[$currencyCode] = new ExchangeRate($fromCurrency, $currency, \App\ValueObjects\Money::create(1, $currency));
                }
            }
        }

        // Check if the requested rate exists

        if (isset($exchangeRates[$cacheKey])) {
            $exchangeRate = $exchangeRates[$cacheKey];
            $this->cacheService->put($cacheKey, $exchangeRate, 1000); // Cache for 60 minutes
        } else {
            throw new \Exception('Exchange rate not found for the specified currencies.');
        }

        return $exchangeRate;
    }
}


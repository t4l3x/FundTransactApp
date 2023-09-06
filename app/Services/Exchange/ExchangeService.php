<?php
declare(strict_types=1);

namespace App\Services\Exchange;

use App\ValueObjects\Currency;
use App\ValueObjects\ExchangeRate;

class ExchangeService
{
    private ExchangeRateProviderFactory $providerFactory;

    public function __construct(ExchangeRateProviderFactory $providerFactory)
    {
        $this->providerFactory = $providerFactory;
    }

    public function getExchangeRate(Currency $fromCurrency, Currency $toCurrency): ExchangeRate
    {
        $provider = $this->providerFactory->create();
        return $provider->getExchangeRate($fromCurrency, $toCurrency);
    }
}

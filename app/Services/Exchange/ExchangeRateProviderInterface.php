<?php
declare(strict_types=1);

namespace App\Services\Exchange;

use App\ValueObjects\Currency;
use App\ValueObjects\ExchangeRate;

interface ExchangeRateProviderInterface
{
    public function getExchangeRate(Currency $fromCurrency, Currency $toCurrency): ExchangeRate;

}

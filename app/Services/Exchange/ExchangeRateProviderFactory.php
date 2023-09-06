<?php
declare(strict_types=1);

namespace App\Services\Exchange;

use App\Services\CacheService;

class ExchangeRateProviderFactory
{
    public static function create(): ExchangeRateProviderInterface {
        // You can define multiple providers and try them in order here.
        return new ExchangeRateProvider(app(CacheService::class)); // You can use app() or Dependency Injection.
    }
}

<?php
declare(strict_types=1);

namespace App\ValueObjects;

class ExchangeRate
{
    private Currency $fromCurrency;
    private Currency $toCurrency;
    private Money $rate;

    public function __construct(Currency $fromCurrency, Currency $toCurrency, Money $rate)
    {
        $this->fromCurrency = $fromCurrency;
        $this->toCurrency = $toCurrency;
        $this->rate = $rate;
    }

    public static function create(Currency $fromCurrency, Currency $toCurrency, Money $rate): ExchangeRate
    {
        return new self($fromCurrency, $toCurrency,$rate);
    }

    public function getFromCurrency(): Currency
    {
        return $this->fromCurrency;
    }

    public function getToCurrency(): Currency
    {
        return $this->toCurrency;
    }

    public function getRate(): Money
    {
        return $this->rate;
    }
}

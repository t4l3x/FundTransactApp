<?php
declare(strict_types=1);

namespace App\ValueObjects;

use Money\Currency as MoneyCurrency;

class Currency
{
    private MoneyCurrency $currency;

    public function __construct(string $isoCode)
    {
        $this->currency = new MoneyCurrency($isoCode);
    }



    public function isoCode(): string
    {
        return $this->currency->getCode();
    }

    public function equals(self $other): bool
    {
        return $this->isoCode() === $other->isoCode();
    }

    public function getCurrency(): MoneyCurrency
    {
        return $this->currency;
    }
}

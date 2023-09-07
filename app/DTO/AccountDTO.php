<?php
declare(strict_types=1);

namespace App\DTO;

use App\ValueObjects\Currency;
use App\ValueObjects\Money;
use Mongo;

class AccountDTO
{
    private string $uuid;
    private Currency $currency;

    protected Money $balance;

    public function __construct(string $uuid, Currency $currencyCode, Money $balance)
    {
        $this->uuid = $uuid;
        $this->currency = $currencyCode;
        $this->balance = $balance;
    }

    public function getId(): string
    {
        return $this->uuid;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }
    public function getBalance(): Money
    {
        return $this->balance;
    }

}

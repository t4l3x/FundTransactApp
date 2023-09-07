<?php
declare(strict_types=1);

namespace App\DTO;

use App\ValueObjects\Currency;

class AccountDTO
{
    private string $uuid;
    private Currency $currencyCode;

    public function __construct(string $uuid, Currency $currencyCode)
    {
        $this->uuid = $uuid;
        $this->currencyCode = $currencyCode;
    }

    public static function fromRequestData(array $requestData): self
    {
        return new self(
            $requestData['id'],
            $requestData['currency_code']
        );
    }

    public function getId(): string
    {
        return $this->uuid;
    }

    public function getCurrencyCode(): Currency
    {
        return $this->currencyCode;
    }
}

<?php
declare(strict_types=1);

namespace App\DTO;

use App\Models\Account;
use App\ValueObjects\Currency;
use App\ValueObjects\ExchangeRate;
use App\ValueObjects\Money;

class TransferRequestDto
{
    protected Account $senderAccount;
    protected Account $receiverAccount;

    protected Money $amount;

    protected Currency $currency;
    protected ExchangeRate $exchangeRate;

    public function __construct(Account $senderAccount, Account $receiverAccount, Money $amount, Currency $currency, ExchangeRate $exchangeRate)
    {
        $this->senderAccount = $senderAccount;
        $this->receiverAccount = $receiverAccount;
        $this->amount = $amount;
        $this->currency = $currency;
        $this->exchangeRate = $exchangeRate;
    }

    public function getSenderAccount(): Account
    {
        return $this->senderAccount;
    }

    public function getReceiverAccount(): Account
    {
        return $this->receiverAccount;
    }

    public function getAmount(): Money
    {
        return $this->amount;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    public function getRate(): ExchangeRate
    {
        return $this->exchangeRate;
    }
}

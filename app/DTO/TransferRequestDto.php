<?php
declare(strict_types=1);

namespace App\DTO;

use App\Models\Account;
use App\ValueObjects\Currency;
use App\ValueObjects\ExchangeRate;
use App\ValueObjects\Money;

class TransferRequestDto
{
    protected AccountDTO $senderAccount;
    protected AccountDTO $receiverAccount;

    protected Money $amount;

    protected Currency $currency;
    protected ExchangeRate $exchangeRate;

    public function __construct(AccountDTO $senderAccount, AccountDTO $receiverAccount, Money $amount, Currency $currency, ExchangeRate $exchangeRate)
    {
        $this->senderAccount = $senderAccount;
        $this->receiverAccount = $receiverAccount;
        $this->amount = $amount;
        $this->currency = $currency;
        $this->exchangeRate = $exchangeRate;
    }

    /**
     * Create a TransferRequestDto from request input data.
     *
     * @param array $requestData
     * @return TransferRequestDto
     */
    public static function fromRequestData(array $requestData): TransferRequestDto
    {
        // Extract and convert request data to value objects
        $senderAccountDTO = new AccountDTO($requestData['from_account_id'], $requestData['from_account_currency']);
        $receiverAccountDTO = new AccountDTO($requestData['to_account_id'], $requestData['to_account_currency']);
        $amount = new Money($requestData['amount'], new Currency($requestData['currency']));
        $currency = new Currency($requestData['currency']); // Adjust as needed
        $exchangeRate = new ExchangeRate($currency, $senderAccountDTO->getCurrencyCode() ,$requestData['rate']);

        // Create and return a TransferRequestDto
        return new self($senderAccountDTO, $receiverAccountDTO, $amount, $currency, $exchangeRate);
    }
    public function getSenderAccount(): AccountDTO
    {
        return $this->senderAccount;
    }

    public function getReceiverAccount(): AccountDTO
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

<?php
declare(strict_types=1);

namespace App\Validations;

use App\DTO\TransferRequestDto;
use App\Services\Exchange\ExchangeRateService;
use App\Services\Exchange\ExchangeService;
use App\ValueObjects\Currency;
use App\ValueObjects\Money;

class DifferentCurrencyTransferValidator implements Contracts\DifferentCurrencyTransferValidator
{
    private ExchangeService $exchangeRateService;

    public function __construct(ExchangeService $exchangeRateService)
    {
        $this->exchangeRateService = $exchangeRateService;
    }

    public function validate(TransferRequestDto $request): void
    {
        $senderAccount = $request->getSenderAccount();

        $amount = $request->getAmount();

        $currency = $request->getCurrency();

        $receiverAccountCurrency = $request->getReceiverAccount()->currency;

        $senderBalance = Money::create($senderAccount->balance, new Currency($senderAccount->currency));

        $amountInSenderCurrency = $this->exchangeRateService->getExchangeRate('fromCurreny','tocurrency');

        if ($senderBalance->compareTo($amountInSenderCurrency) < 0) {
            throw new ValidationException("Insufficient funds in sender's account.");
        }

    }
}

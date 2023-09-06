<?php
declare(strict_types=1);

namespace App\Validations;

use App\DTO\TransferRequestDto;
use App\Exceptions\Validations\ValidationException;
use App\Services\Exchange\ExchangeRateService;
use App\Validations\Contracts\TransferValidation;
use App\ValueObjects\Currency;
use App\ValueObjects\Money;

class TransferValidator implements TransferValidation {
    private ExchangeRateService $exchangeRateService;

    public function __construct(ExchangeRateService $exchangeRateService)
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

        if ($currency === $receiverAccountCurrency) {
            // Same currency transfer validation
            $this->validateSameCurrencyTransfer($senderBalance, $amount);
        } else {
            // Different currency transfer validation
            $this->validateDifferentCurrencyTransfer($senderBalance, $amount, $currency);
        }
    }

    private function validateSameCurrencyTransfer(Money $senderBalance, Money $amount): void
    {
        if ($senderBalance->toIntegerAmount() < $amount->toIntegerAmount()) {
            throw new ValidationException("Insufficient funds in sender's account.");
        }
    }

    private function validateDifferentCurrencyTransfer(Money $senderBalance, Money $amount, Currency $currency): void
    {
        $amountInSenderCurrency = $this->exchangeRateService->convertToCurrency($amount, $currency);

        if ($senderBalance->compareTo($amountInSenderCurrency) < 0) {
            throw new ValidationException("Insufficient funds in sender's account.");
        }
    }

}

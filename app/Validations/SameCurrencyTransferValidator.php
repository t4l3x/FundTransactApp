<?php
declare(strict_types=1);

namespace App\Validations;

use App\DTO\TransferRequestDto;
use App\Exceptions\Validations\ValidationException;
use App\ValueObjects\Currency;
use App\ValueObjects\Money;

class SameCurrencyTransferValidator implements Contracts\SameCurrencyTransferValidator
{

    public function validate(TransferRequestDto $request): void
    {
        $senderAccount = $request->getSenderAccount();
        $amount = $request->getAmount();

        $senderBalance = Money::create($senderAccount->balance, new Currency($senderAccount->currency));

        if ($senderBalance->toIntegerAmount() < $amount->toIntegerAmount()) {
            throw new ValidationException("Insufficient funds in sender's account.");
        }
    }
}

<?php
declare(strict_types=1);

namespace App\Validations;

use App\DTO\TransferRequestDto;
use App\Exceptions\Validations\ValidationException;
use App\Validations\Contracts\MatchingCurrencyValidation;

class MatchingCurrencyValidator implements MatchingCurrencyValidation
{

    public function validate(TransferRequestDto $request): void
    {
        $receiverCurrency = $request->getReceiverAccount()->currency;

        if (!$request->getCurrency()->equals($receiverCurrency)) {
            throw new ValidationException("Currency of funds must match receiver's account currency.");
        }
    }
}

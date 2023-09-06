<?php
declare(strict_types=1);

namespace App\Validations\Contracts;

use App\DTO\TransferRequestDto;

interface TransferValidation
{
    public function validate(TransferRequestDto $request): void;
}

<?php
declare(strict_types=1);

namespace App\Exceptions\Validations;

use RuntimeException;
use Throwable;

class ValidationException extends RuntimeException
{
    // ...
    public function __construct(string $errorMessage, $code = 422, Throwable $previous = null)
    {
        parent::__construct($errorMessage, $code, $previous);
    }
}



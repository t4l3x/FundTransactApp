<?php
declare(strict_types=1);

namespace App\Exceptions;

use RuntimeException;
use Throwable;

class NoExchangeRateProviderException
    extends RuntimeException
{
    // ...
    public function __construct(string $errorMessage, $code = 422, Throwable $previous = null)
    {
        parent::__construct($errorMessage, $code, $previous);
    }
}

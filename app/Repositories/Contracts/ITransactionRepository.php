<?php
declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\Account;
use App\Models\Transactions;
use App\ValueObjects\Currency;
use App\ValueObjects\ExchangeRate;
use App\ValueObjects\Money;
use Illuminate\Support\Collection;

interface ITransactionRepository
{
    public function createTransaction(string $senderAccountUuid, string $receiverAccountUuid, Money $amount, Currency $currency, ExchangeRate $exchangeRate): Transactions;
    public function getTransactionsByAccountId(string $account, int $limit = 10, int $offset = 0): Collection;


}

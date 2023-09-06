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
    public function createTransaction(Account $senderAccount, Account $receiverAccount, Money $amount, Currency $currency, ExchangeRate $exchangeRate): Transactions;
    public function getTransactionsByAccountId(Account $account, int $limit = 10, int $offset = 0): Collection;


}

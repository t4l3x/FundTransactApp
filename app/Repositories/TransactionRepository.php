<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Account;
use App\Models\Transactions;
use App\Repositories\Contracts\ITransactionRepository;
use App\ValueObjects\Currency;
use App\ValueObjects\ExchangeRate;
use App\ValueObjects\Money;
use Illuminate\Support\Collection;

class TransactionRepository extends BaseRepository implements ITransactionRepository
{
    public function __construct(Transactions $model)
    {
        parent::__construct($model);
    }


    public function createTransaction(Account $senderAccount, Account $receiverAccount, Money $amount, Currency $currency, ExchangeRate $exchangeRate): Transactions
    {

       return $this->model->create([
            'sender_account_id' => $senderAccount->id,
            'receiver_account_id' => $receiverAccount->id,
            'amount' => $amount->toDecimalAmount(),
            'currency' => $currency->getCurrency(),
            'exchange_rate' => $exchangeRate->getRate()->toDecimalAmount()
        ]);

    }

    public function getTransactionsByAccountId(Account $account, int $limit = 10, int $offset = 0): Collection
    {
        return $this->model
            ->where('sender_account_id', $account->id)
            ->orWhere('receiver_account_id', $account->id)
            ->orderByDesc('created_at')
            ->limit($limit)
            ->offset($offset)
            ->get();
    }

    public function getById(string $id): ?Transactions
    {
        return $this->model->find($id);
    }
}

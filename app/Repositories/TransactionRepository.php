<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Account;
use App\Models\Transactions;
use App\Repositories\Contracts\ITransactionRepository;
use App\ValueObjects\Currency;
use App\ValueObjects\Money;
use Illuminate\Support\Collection;

class TransactionRepository extends BaseRepository implements ITransactionRepository
{
    public function __construct(Transactions $transaction)
    {
        parent::__construct($transaction);
    }


    public function createTransaction(Account $senderAccount, Account $receiverAccount, Money $amount, Currency $currency): Transactions
    {
        $transaction = new Transactions([
            'sender_account_id' => $senderAccount->id,
            'receiver_account_id' => $receiverAccount->id,
            'amount' => $amount->toDecimalAmount(),
            'currency' => $currency->getCurrency(),
        ]);

        $transaction->save();

        return $transaction;
    }

    public function getTransactionsByAccountId(string $accountId, int $limit = 10, int $offset = 0): Collection
    {
        return $this->model
            ->where('sender_account_id', $accountId)
            ->orWhere('receiver_account_id', $accountId)
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

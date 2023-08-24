<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Account;
use App\Models\Transaction;
use App\Repositories\Contracts\ITransactionRepository;
use App\ValueObjects\Money;
use Illuminate\Support\Collection;

class TransactionRepository extends BaseRepository implements ITransactionRepository
{
    public function __construct(Transaction $transaction)
    {
        parent::__construct($transaction);
    }


    public function createTransaction(Account $senderAccount, Account $receiverAccount, Money $amount): Transaction
    {
        $transaction = new Transaction([
            'sender_account_id' => $senderAccount->id,
            'receiver_account_id' => $receiverAccount->id,
            'amount' => $amount,
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
}

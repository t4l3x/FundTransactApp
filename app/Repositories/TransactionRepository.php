<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Transaction;
use App\Repositories\Contracts\ITransactionRepository;

class TransactionRepository extends BaseRepository implements ITransactionRepository
{
    public function __construct(Transaction $transaction)
    {
        parent::__construct($transaction);
    }
}

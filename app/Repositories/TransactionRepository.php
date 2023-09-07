<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Account;
use App\Models\Transactions;
use App\Repositories\Contracts\ITransactionRepository;
use App\ValueObjects\Currency;
use App\ValueObjects\ExchangeRate;
use App\ValueObjects\Money;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class TransactionRepository extends BaseRepository implements ITransactionRepository
{
    public function __construct(Transactions $model)
    {
        parent::__construct($model);
    }


    public function createTransaction(string $senderAccountUuid, string $receiverAccountUuid, Money $amount, Currency $currency, ExchangeRate $exchangeRate): Transactions
    {

       return $this->model->create([
            'sender_account_id' => $senderAccountUuid,
            'receiver_account_id' => $receiverAccountUuid,
            'amount' => $amount->toDecimalAmount(),
            'currency' => $currency->getCurrency(),
            'exchange_rate' => $exchangeRate->getRate()->toDecimalAmount()
        ]);

    }

    public function getTransactionsByAccountId(string $accountId, int $offset = 5, int $limit = 5): Collection
    {
        // Build the query
        $query = $this->model
            ->where('sender_account_id', $accountId)
            ->orWhere('receiver_account_id', $accountId)
            ->orderByDesc('created_at');

        // Paginate the query using simplePaginate (without total count)
        $paginator = $query->paginate($limit);

        return collect($paginator);
    }

    public function getById(string $id): ?Transactions
    {
        return $this->model->find($id);
    }
}

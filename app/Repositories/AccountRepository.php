<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Account;
use App\Repositories\Contracts\IAccountRepository;
use App\ValueObjects\Money;
use Illuminate\Support\Collection;

class AccountRepository extends BaseRepository implements IAccountRepository
{
    public function __construct(Account $model)
    {
        parent::__construct($model);
    }

    public function getAllByClientId(string $userId): Collection
    {
        return $this->model->where('user_id', $userId)->get();
    }

    public function getById(string $accountId): ?Account
    {
        return $this->model->find($accountId);
    }

    public function updateBalance(Account $account, Money $amount): void
    {
        // Assuming you have a 'balance' attribute in your Account model
        $account->balance = $amount;
        $account->save();
    }
}

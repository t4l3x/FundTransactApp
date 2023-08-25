<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Account;
use App\Models\User;
use App\Repositories\Contracts\IAccountRepository;
use App\ValueObjects\Currency;
use App\ValueObjects\Money;
use Illuminate\Support\Collection;

class AccountRepository extends BaseRepository implements IAccountRepository
{
    public function __construct(Account $model)
    {
        parent::__construct($model);
    }

    public function getAllByUser(User $userId): Collection
    {
        return $this->model->where('user_id', $userId->id)->get();
    }

    public function getById(Account $accountId): ?Account
    {
        return $this->model->find($accountId->id);
    }

    public function updateBalance(Account $account, Money $amount): bool
    {
        $integerAmount = $amount->toDecimalAmount();

        $result = $this->model
            ->where('id', $account->id)
            ->update(['balance' => $integerAmount]);

        return $result !== false;
    }
}

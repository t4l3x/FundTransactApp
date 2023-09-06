<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Account;
use App\Models\User;
use App\Repositories\Contracts\IAccountRepository;
use App\ValueObjects\Currency;
use App\ValueObjects\Money;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

class AccountRepository extends BaseRepository implements IAccountRepository
{
    public function __construct(Account $model)
    {
        parent::__construct($model);
    }

    public function getAllByUser(User $userId): Collection
    {
      return  $result = $this->model->where('user_id', $userId->id)->get();
    }

    public function getById(Account $accountId): ?Account
    {
        return $this->model->find($accountId->id);
    }


    public function updateBalance(Account $account, Money $amount): bool
    {

        $result = $this->model
            ->where('id', $account->id)
            ->update(['balance' => $amount->toDecimalAmount()]);

        return $result !== false;
    }

    /**
     * Check if a user has an account in a specific currency.
     *
     * @param User $user
     * @param Currency $currency
     * @return bool
     */
    public function userHasAccountInCurrency(User $user, Currency $currency): bool
    {
        try {
            $this->model->where('user_id', $user->id)
                ->where('currency', $currency->getCurrency())
                ->firstOrFail();

            return true; // Account exists
        } catch (ModelNotFoundException $e) {
            return false; // Account doesn't exist
        }
    }
}

<?php
declare(strict_types=1);

namespace App\Services;


use App\Models\User;
use App\Repositories\Contracts\IAccountRepository;
use App\Repositories\Contracts\ITransactionRepository;
use App\ValueObjects\Currency;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;


class AccountService
{
    protected IAccountRepository $accountRepository;
    private  ITransactionRepository $transactionRepository;

    public function __construct(IAccountRepository $accountRepository, ITransactionRepository $transactionRepository)
    {
        $this->accountRepository = $accountRepository;
        $this->transactionRepository = $transactionRepository;
    }

    /**
     * @throws \Exception
     */
    public function createAccount(User $user, Currency $currency): Model
    {
        // Check if the user already has an account in the same currency
        if ($this->accountRepository->userHasAccountInCurrency($user, $currency)) {
            throw new \Exception("User already has an account in {$currency->getCurrency()}");
        }

        // Create a new account
        return $this->accountRepository->create([
            'user_id' => $user->id,
            'currency' => $currency->getCurrency(),
            'balance' => 0,
        ]);
    }

    public function getClientAccounts($clientId): Collection
    {
        // Delegate the call to the repository
        return $this->accountRepository->getAllByUser($clientId);
    }

    public function getTransactionHistory($accountId, $offset, $limit): Collection
    {
        return $this->transactionRepository->getTransactionsByAccountId($accountId, $offset, $limit);
    }

}

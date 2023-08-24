<?php
declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\Account;
use App\ValueObjects\Money;
use Illuminate\Support\Collection;

interface IAccountRepository
{
    public function getAllByClientId(string $clientId): Collection;

    public function getById(string $accountId): ?Account;
    public function updateBalance(Account $account, Money $amount): bool;

}

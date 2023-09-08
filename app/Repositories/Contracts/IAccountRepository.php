<?php
declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\DTO\AccountDTO;
use App\Models\Account;
use App\Models\User;
use App\ValueObjects\Money;
use Illuminate\Support\Collection;

interface IAccountRepository
{
    public function getAllByUser(User $userIdId): Collection;

    public function getById(string $accountId): ?Account;

    public function updateBalance(AccountDTO $account, Money $amount): bool;

}

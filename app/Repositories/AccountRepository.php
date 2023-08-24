<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Account;
use App\Repositories\Contracts\IAccountRepository;

class AccountRepository extends BaseRepository implements IAccountRepository
{
    public function __construct(Account $account)
    {
        parent::__construct($account);
    }
}

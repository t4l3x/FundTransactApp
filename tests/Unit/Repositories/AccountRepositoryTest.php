<?php
declare(strict_types=1);

namespace Repositories;

use App\Models\Account;
use App\Models\User;
use App\Repositories\AccountRepository;
use App\ValueObjects\Currency;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\ValueObjects\Money;

class AccountRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected AccountRepository $accountRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->accountRepository = new AccountRepository(new Account());
    }


    public function testGetAllByClientId()
    {
        $user = User::factory()->create(); // Create a fake user

        $accounts = Account::factory()->count(5)->create(['user_id' => $user->id]);

        $fetchedAccounts = $this->accountRepository->getAllByUser($user);

        $this->assertCount(5, $fetchedAccounts);
    }

    public function testGetById()
    {
        $account = Account::factory()->create(); // Create a fake account

        $fetchedAccount = $this->accountRepository->getById($account);

        $this->assertNotNull($fetchedAccount);

        $this->assertEquals($account->id, $fetchedAccount->id);
    }

    public function testUpdateBalance()
    {
        // Create a fake account
        $account = Account::factory()->create();

        // Amount to update the balance
        $updatedBalance = Money::create('1500', new Currency('USD'));

        // Update the balance

        $this->accountRepository->updateBalance($account, $updatedBalance);

        // Fetch the account from the database
        $updatedAccount = $this->accountRepository->getById($account);

        // Assert that the balance was updated correctly
        $this->assertEquals($updatedBalance->getAmount(), $updatedAccount->balance->getAmount());

        // Additional assertion: Check if the returned value from updateBalance is true
        $this->assertTrue($this->accountRepository->updateBalance($account, $updatedBalance));
    }

}

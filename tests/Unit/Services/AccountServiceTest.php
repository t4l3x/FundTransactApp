<?php
declare(strict_types=1);

namespace Services;

use App\Models\Account;
use App\Models\User;
use App\Repositories\AccountRepository;
use App\Repositories\Contracts\IAccountRepository;
use App\Services\AccountService;
use App\ValueObjects\Currency;
use Illuminate\Database\Eloquent\Model;
use Tests\TestCase;

class AccountServiceTest extends TestCase
{
    protected AccountService $accountService;
    protected IAccountRepository $accountRepository;

    public function setUp(): void
    {
        parent::setUp();

        // Mock the account repository
        $this->accountRepository = $this->createMock(AccountRepository::class);

        // Create an instance of the AccountService with the mock repository
        $this->accountService = new AccountService($this->accountRepository);
    }

    public function testCreateAccountWhenUserHasNoAccountInCurrency()
    {
        // Arrange
        $user = User::factory()->create();
        $currency = new Currency('USD');

        // Assume the repository method returns false, indicating no existing account
        $this->accountRepository->expects($this->once())
            ->method('userHasAccountInCurrency')
            ->with($user, $currency)
            ->willReturn(false);

        // Mock the repository's create method to return a new account
        $this->accountRepository->expects($this->once())
            ->method('create')
            ->willReturn(new Account()); // You can customize this for your needs

        // Act
        $result = $this->accountService->createAccount($user, $currency);

        // Assert
        $this->assertInstanceOf(Model::class, $result);
    }

    public function testCreateAccountWhenUserHasAccountInCurrency()
    {
        // Arrange
        $user = User::factory()->create();
        $currency = new Currency('USD');

        // Assume the repository method returns true, indicating an existing account
        $this->accountRepository->expects($this->once())
            ->method('userHasAccountInCurrency')
            ->with($user, $currency)
            ->willReturn(true);

        // Expect an exception to be thrown
        $this->expectException(\Exception::class);

        // Act
        $this->accountService->createAccount($user, $currency);
    }



}

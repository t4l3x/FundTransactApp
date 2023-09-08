<?php
declare(strict_types=1);

namespace Services;

use App\DTO\AccountDTO;
use App\DTO\TransferRequestDto;
use App\Models\Account;
use App\Models\Transactions;
use App\Repositories\AccountRepository;
use App\Repositories\Contracts\IAccountRepository;
use App\Repositories\Contracts\ITransactionRepository;
use App\Repositories\TransactionRepository;
use App\Services\Exchange\ExchangeService;
use App\Services\FundsTransferService;
use App\ValueObjects\Currency;
use App\ValueObjects\ExchangeRate;
use App\ValueObjects\Money;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class FundsTransferServiceTest extends TestCase
{
    use DatabaseTransactions;
    protected FundsTransferService $fundsTransferService;
    protected AccountRepository $accountRepository;
    protected TransactionRepository $transactionRepository;
    protected ExchangeService $exchangeService;

    public function setUp(): void
    {
        parent::setUp();

        // Initialize your actual repository and service instances here
        $this->accountRepository = new AccountRepository(new Account());
        $this->transactionRepository = new TransactionRepository(new Transactions());


        // Create an instance of the FundsTransferService with your actual dependencies
        $this->fundsTransferService = new FundsTransferService(
            $this->accountRepository,
            $this->transactionRepository,

        );
    }

    // Test case for a successful transfer with the same currency (Scenario 1)
    public function testTransferFundsDifferentCurrency()
    {
        // Arrange
        // Create sender and receiver accounts with different currencies
        $senderAccount = Account::factory()->create(['currency' => 'AZN']);
        $receiverAccount = Account::factory()->create(['currency' => 'USD']);

        $amount = Money::create(100, new Currency('USD')); // Amount in USD

        $exchangeRate = Money::create(1.70, new Currency('AZN'));

        $senderAccountDto = new AccountDto($senderAccount->id,$senderAccount->currency, $senderAccount->balance);
        $receiverAccountDto = new AccountDto($receiverAccount->id,$receiverAccount->currency,$receiverAccount->balance);
        $exchangeRate = ExchangeRate::create(new Currency('USD'), new Currency('AZN'), $exchangeRate); // Exchange rate from USD to EUR
        $request = new TransferRequestDto($senderAccountDto, $receiverAccountDto, $amount, new Currency('USD'), $exchangeRate);

        // Mock any necessary repository or service calls if needed

        // Act
        $result = $this->fundsTransferService->transferFunds($request);

        // Assert
        $this->assertTrue($result);
        // Additional assertions as needed
    }

    public function testTransferFundsDifferentCurrencyResult()
    {
        // Arrange
        // Create sender and receiver accounts with different currencies

        $senderAccount = Account::factory()->create(['currency' => 'AZN']);
        $receiverAccount = Account::factory()->create(['currency' => 'USD']);

        // Set the initial balances of sender and receiver accounts
        $senderAccount->balance = Money::create(1000, new Currency('AZN'))->toDecimalAmount(); // 1000 AZN
        $receiverAccount->balance = Money::create(500, new Currency('USD'))->toDecimalAmount(); // 500 USD

        // Save the initial balances to the database
        $senderAccount->save();
        $receiverAccount->save();

        $amountInUSD = Money::create(10, new Currency('USD')); // Amount in USD

        // Define the exchange rate
        $exchangeRate = Money::create(170, new Currency('AZN')); // 1 USD = 1.70 AZN

        $exchangeRate = ExchangeRate::create(new Currency('USD'), new Currency('AZN'), $exchangeRate); // Exchange rate from USD to AZN

        $senderAccountDto = new AccountDto($senderAccount->id,$senderAccount->currency, $senderAccount->balance);
        $receiverAccountDto = new AccountDto($receiverAccount->id,$receiverAccount->currency,$receiverAccount->balance);

        $request = new TransferRequestDto($senderAccountDto, $receiverAccountDto, $amountInUSD, new Currency('USD'), $exchangeRate);


        // Act

        $result = $this->fundsTransferService->transferFunds($request);

        // Assert
        $this->assertTrue($result);

        // Reload sender and receiver accounts from the database to get the updated balances
        $senderAccount->refresh();

        $receiverAccount->refresh();



        // Check if the balances have been updated correctly


        $this->assertEquals(983, $senderAccount->balance->getAmount()); // Sender balance should be 1000 - 100 = 900 AZN
        $this->assertEquals(510, $receiverAccount->balance->getAmount()); // Receiver balance should be 500 + 100*1.70 = 570 USD
    }



    // You can add more test cases for different scenarios, including currency conversion scenarios.
}

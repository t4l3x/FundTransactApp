<?php
declare(strict_types=1);

namespace Repositories;

use App\Models\Account;
use App\Models\Transactions;
use App\Repositories\TransactionRepository;
use App\ValueObjects\Currency;
use App\ValueObjects\Money;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected TransactionRepository $transactionRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->transactionRepository = new TransactionRepository(new Transactions());
    }

    public function testCreateTransaction()
    {
        // Create sender and receiver accounts
        $senderAccount = Account::factory()->create();
        $receiverAccount = Account::factory()->create();

        // Create a fake transaction amount
        $currency = new Currency('USD');
        $transactionAmount = Money::create('100050', $currency);

        // Create a transaction
        $transaction = $this->transactionRepository->createTransaction(
            $senderAccount,
            $receiverAccount,
            $transactionAmount,
            $currency
        );

        // Assert that the transaction was created
        $this->assertDatabaseHas('transactions', [
            'sender_account_id' => $senderAccount->id,
            'receiver_account_id' => $receiverAccount->id,
            'amount' => $transactionAmount->toDecimalAmount(),
            'currency' => $currency->getCurrency()
        ]);

        // Fetch the created transaction from the database
        $createdTransaction = $this->transactionRepository->getById($transaction->id);

        // Assert that the fetched transaction matches the created one
        $this->assertEquals($transaction->id, $createdTransaction->id);
        $this->assertEquals($transaction->sender_account_id, $createdTransaction->sender_account_id);
        $this->assertEquals($transaction->receiver_account_id, $createdTransaction->receiver_account_id);
        $this->assertEquals($transactionAmount->toDecimalAmount(), $createdTransaction->amount);
    }


    public function testGetTransactionsByAccountId()
    {
        // Create a fake account
        $account = Account::factory()->create();

        // Create some fake transactions for the account
        Transactions::factory()->count(5)->create(['sender_account_id' => $account->id]);
        Transactions::factory()->count(5)->create(['receiver_account_id' => $account->id]);

        // Get transactions for the account
        $transactions = $this->transactionRepository->getTransactionsByAccountId($account->id);

        // Perform assertions
        $this->assertCount(10, $transactions);
    }
}

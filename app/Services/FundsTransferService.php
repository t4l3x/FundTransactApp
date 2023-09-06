<?php
declare(strict_types=1);

namespace App\Services;

use App\DTO\TransferRequestDto;
use App\Models\Account;
use App\Repositories\Contracts\IAccountRepository;
use App\Repositories\Contracts\ITransactionRepository;
use App\Services\Exchange\ExchangeRateProviderFactory;
use App\Services\Exchange\ExchangeRateProviderInterface;
use App\Services\Exchange\ExchangeRateService;
use App\Services\Exchange\ExchangeService;
use App\ValueObjects\Money;
use Illuminate\Support\Facades\DB;

class FundsTransferService
{
    protected IAccountRepository $accountRepository;
    protected ITransactionRepository $transactionRepository;

    public function __construct(IAccountRepository $accountRepository, ITransactionRepository $transactionRepository)
    {
        $this->accountRepository = $accountRepository;
        $this->transactionRepository = $transactionRepository;


    }


    public function transferFunds(TransferRequestDto $request): bool
    {
        return DB::transaction(function () use ($request) {
            try {
                // 1. Validate the transfer
//                $this->validateTransfer($request->getSenderAccount(), $request->getAmount());

                // 2. Execute the transfer
                $this->executeTransfer($request);

                // 3. Dispatch events related to the fund transfer (if needed)

                return true; // Successful transfer within the transaction
            } catch (\Throwable $e) {
                // Log the error
                logger()->error('Error transferring funds: ' . $e->getMessage());

                // Optionally, you can throw the exception here if you want to propagate it
                // throw $e;

                return false; // Indicate failure
            }
        }, 5); // The '5' is the number of times the transaction should be retried in case of deadlock
    }

    protected function validateTransfer(Account $senderAccount, Money $amount): void
    {
        $senderBalance = $senderAccount->balance;

        if ($senderBalance->isLessThan($amount)) {
            throw new InsufficientFundsException("Insufficient funds in sender's account.");
        }
    }

    protected function executeTransfer(TransferRequestDto $request): void
    {
        $transaction = $this->transactionRepository->createTransaction(
            $request->getSenderAccount(),
            $request->getReceiverAccount(),
            $request->getAmount(),
            $request->getCurrency(),
            $request->getRate()
        );

        $this->updateAccountBalances($request);
    }

    protected function updateAccountBalances(TransferRequestDto $request): void
    {
        $senderAccount = $request->getSenderAccount();
        $receiverAccount = $request->getReceiverAccount();
        $amount = $request->getAmount();
        $exchangeRate = $request->getRate();

        // Calculate the amount in the receiver's currency using the multiply method
        $substractAmountSender = Money::create($amount->multiply($exchangeRate->getRate())->getAmount(), $senderAccount->currency);

        // Use a database transaction to ensure consistency during balance updates
        DB::transaction(function () use ($senderAccount, $receiverAccount, $amount, $substractAmountSender) {
            try {
                // Perform balance updates within the transaction
                $senderNewBalance = $senderAccount->balance->subtract($substractAmountSender);
                $receiverNewBalance = $receiverAccount->balance->add($amount);

                // Update sender and receiver balances

                $this->accountRepository->updateBalance($senderAccount, $senderNewBalance);
                $this->accountRepository->updateBalance($receiverAccount, $receiverNewBalance);

            } catch (\Throwable $e) {
                // Log the error
                logger()->error('Error updating account balances: ' . $e->getMessage());

                // Optionally, you can throw the exception here if you want to propagate it
                // throw $e;
            }
        });
    }

}

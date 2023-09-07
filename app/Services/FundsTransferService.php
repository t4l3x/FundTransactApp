<?php
declare(strict_types=1);

namespace App\Services;

use App\DTO\TransferRequestDto;
use App\Exceptions\Validations\ValidationException;
use App\Repositories\Contracts\IAccountRepository;
use App\Repositories\Contracts\ITransactionRepository;
use App\ValueObjects\Money;
use Illuminate\Support\Facades\DB;
use Throwable;

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
        $this->validateTransfer($request);

        return DB::transaction(function () use ($request) {
            try {

                $this->executeTransfer($request);

                // 3. Dispatch events related to the fund transfer (if needed)

                return true; // Successful transfer within the transaction
            } catch (Throwable $e) {
                // Log the error
                logger()->error('Error transferring funds: ' . $e->getMessage());

                // Optionally, you can throw the exception here if you want to propagate it
                throw $e;

            }
        }, 5); // The '5' is the number of times the transaction should be retried in case of deadlock
    }

    protected function validateTransfer(TransferRequestDto $request): void
    {

        if (!$request->getCurrency()->equals($request->getReceiverAccount()->getCurrency())) {
            throw new ValidationException("Currency of funds must match receiver's account currency.");
        }

        if ($request->getSenderAccount()->getBalance() < $request->getAmount()) {
            throw new ValidationException("Insufficient funds in sender's account.");
        }
    }


    /**
     * @throws Throwable
     */
    protected function executeTransfer(TransferRequestDto $request): void
    {
        $transaction = $this->transactionRepository->createTransaction(
            $request->getSenderAccount()->getId(),
            $request->getReceiverAccount()->getId(),
            $request->getAmount(),
            $request->getCurrency(),
            $request->getRate()
        );

        $this->updateAccountBalances($request);
    }

    /**
     * @throws Throwable
     */
    protected function updateAccountBalances(TransferRequestDto $request): void
    {
        $senderAccount = $this->accountRepository->getById($request->getSenderAccount()->getId());
        $receiverAccount = $this->accountRepository->getById($request->getReceiverAccount()->getId());
        $amount = $request->getAmount();
        $exchangeRate = $request->getRate();

        // Calculate the amount in the receiver's currency using the multiply method
        $substractAmountSender = Money::create($amount->multiply($exchangeRate->getRate())->getAmount(), $senderAccount->currency);

        // Use a database transaction to ensure consistency during balance updates
        DB::transaction(function () use ($senderAccount, $receiverAccount, $amount, $substractAmountSender, $request) {
            try {
                // Perform balance updates within the transaction
                $senderNewBalance = $senderAccount->balance->subtract($substractAmountSender);
                $receiverNewBalance = $receiverAccount->balance->add($amount);

                // Update sender and receiver balances

                $this->accountRepository->updateBalance($senderAccount, $senderNewBalance);
                $this->accountRepository->updateBalance($receiverAccount, $receiverNewBalance);

            } catch (Throwable $e) {
                // Log the error
                logger()->error('Error updating account balances: ' . $e->getMessage());

                // Optionally, you can throw the exception here if you want to propagate it
                throw $e;
            }
        });
    }

}

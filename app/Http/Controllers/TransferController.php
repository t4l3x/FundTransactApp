<?php

namespace App\Http\Controllers;

use App\DTO\AccountDTO;
use App\DTO\TransferRequestDto;
use App\Http\Requests\TransferRequest;
use App\Services\AccountService;
use App\Services\Exchange\ExchangeService;
use App\Services\FundsTransferService;
use App\ValueObjects\Currency;
use App\ValueObjects\Money;
use Illuminate\Http\JsonResponse;


class TransferController extends Controller
{
    //
    protected FundsTransferService $fundsTransferService;
    protected ExchangeService $exchangeService;

    protected AccountService $accountService;

    public function __construct(FundsTransferService $fundsTransferService, ExchangeService $exchangeService, AccountService $accountService)
    {
        $this->fundsTransferService = $fundsTransferService;
        $this->exchangeService = $exchangeService;
        $this->accountService = $accountService;
    }

    public function transferFunds(TransferRequest $request): JsonResponse
    {

        // Extract validated data from the request
        $validatedData = $request->validated();

        $senderAccount = $this->accountService->getAccountById($request['from_account_id']);
        $senderAccountDTO = new AccountDTO($senderAccount->id, $senderAccount->currency, $senderAccount->balance);

        $receiverAccount = $this->accountService->getAccountById($request['to_account_id']);
        $receiverAccountDto = new AccountDTO($receiverAccount->id, $receiverAccount->currency,$senderAccount->balance);


        $validatedData['from_account_currency'] = $senderAccount->currency;
        $validatedData['to_account_currency'] = $senderAccount->currency;

        $validatedData['from_account_balance'] = $senderAccount->balance;
        $validatedData['to_account_balance'] = $senderAccount->balance;
        // Get the target currency from the request
        $toCurrency = new Currency($validatedData['currency']);

        // Calculate the exchange rate if currencies are different
        if (!$senderAccountDTO->getCurrency()->equals($toCurrency)) {
            $validatedData['rate'] = $this->exchangeService->getExchangeRate($toCurrency, $senderAccountDTO->getCurrency())->getRate()->getAmount();
            $validatedData['rate'] = Money::create($validatedData['rate'], $toCurrency);
        } else {
            $validatedData['rate'] = Money::create(1, $senderAccountDTO->getCurrency()); // If sender and receiver have the same currency, no conversion needed
        }

        // Create a TransferRequestDto with validated data and exchange rate
        $transferDto = TransferRequestDto::fromRequestData($validatedData);

        // Perform the funds transfer and check if it's successful
        $success = $this->fundsTransferService->transferFunds($transferDto);

        if ($success) {
            return response()->json(['message' => 'Funds transferred successfully']);
        } else {
            return response()->json(['message' => 'Funds transfer failed'], 500);
        }
    }
}

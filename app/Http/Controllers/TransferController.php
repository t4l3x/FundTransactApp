<?php

namespace App\Http\Controllers;

use App\DTO\AccountDTO;
use App\DTO\TransferRequestDto;
use App\Http\Requests\TransferRequest;
use App\Services\AccountService;
use App\Services\Exchange\ExchangeService;
use App\Services\FundsTransferService;
use App\ValueObjects\Currency;
use App\ValueObjects\ExchangeRate;
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

        $validatedData['sender_account'] = $this->accountService->getAccountById($request['from_account_id']);
        $validatedData['receiver_account'] = $this->accountService->getAccountById($request['to_account_id']);
        $validatedData['currency'] = $toCurrency = new Currency($validatedData['currency']);
        $validatedData['amount'] = Money::create($validatedData['amount'], $validatedData['currency']);
        // Get the target currency from the request


        // Calculate the exchange rate if currencies are different
        if (!$validatedData['sender_account']->getCurrency()->equals($toCurrency)) {
            $validatedData['rate'] = $this->exchangeService->getExchangeRate($toCurrency, $validatedData['sender_account']->getCurrency());
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

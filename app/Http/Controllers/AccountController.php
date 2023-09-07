<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAccountRequest;
use App\Http\Resources\AccountResource;
use App\Http\Resources\TransactionResource;
use App\Models\Account;
use App\Models\User;
use App\Services\AccountService;
use App\ValueObjects\Currency;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\UnauthorizedException;


class AccountController extends Controller
{
    //
    protected AccountService $accountService;

    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }
    // Get a list of accounts for a client
    public function getClientAccounts(Request $request, User $user): AnonymousResourceCollection
    {

        $accounts = $this->accountService->getClientAccounts($user);

        return AccountResource::collection($accounts);
    }
    // Create a new account for a client

    /**
     * @throws \Exception
     */
    public function createAccount(CreateAccountRequest $request): AccountResource
    {
        // Check if a user is authenticated

        if (!$user = Auth::user()) {
            throw new UnauthorizedException('Unauthorized');
        }

        // Validate the request data here, if needed

        $currency = new Currency($request->input('currency')); // Assuming currency is sent in the request
        $account = $this->accountService->createAccount($user, $currency);

        return new AccountResource($account);
    }

    public function getTransactionHistory(Account $account, Request $request): AnonymousResourceCollection
    {
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 10);

        $transactions = $this->accountService->getTransactionHistory($account->id, $offset, $limit);

        return TransactionResource::collection($transactions);
    }
}

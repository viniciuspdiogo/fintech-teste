<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\AccountNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionsRequest;
use App\Http\Resources\AccountsTransactionResource;
use App\Services\TransactionService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TransactionsController extends Controller
{
    public function __construct(private readonly TransactionService $transactionService)
    {
    }

    /**
     * @param TransactionsRequest $request
     * @return AnonymousResourceCollection
     * @throws AccountNotFoundException
     */
    public function listTransactions(TransactionsRequest $request): AnonymousResourceCollection
    {
        $account = $request->user()->account ?? throw new AccountNotFoundException();

        $transactions = $this->transactionService->listTransactionsByAccount(
            $account,
            $request->boolean('limit')
        );

        return AccountsTransactionResource::collection($transactions);
    }
}

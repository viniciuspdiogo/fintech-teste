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
        $startDate = $request->filled('start_date') ? $request->string('start_date')->toString() : null;
        $endDate = $request->filled('end_date') ? $request->string('end_date')->toString() : null;

        $transactions = $this->transactionService->listTransactionsByAccount(
            $account,
            $request->boolean('limit'),
            $startDate,
            $endDate,
        );

        return AccountsTransactionResource::collection($transactions);
    }
}

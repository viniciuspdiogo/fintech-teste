<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\AccountNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\AccountsPostRequest;
use App\Http\Resources\AccountsPostResource;
use App\Models\Account;
use App\Services\AccountService;
use Throwable;

class AccountsController extends Controller
{

    /**
     * @param AccountsPostRequest $request
     * @return AccountsPostResource
     * @throws AccountNotFoundException
     */
    public function findByUser(AccountsPostRequest $request)
    {
        $account = $request->user()->account ?? throw new AccountNotFoundException();
        return (new AccountsPostResource($account->refresh()))
            ->additional(['message' => 'Conta encontrada com sucesso.']);
    }
    /**
     * @param AccountsPostRequest $request
     * @param AccountService $service
     * @return AccountsPostResource
     * @throws AccountNotFoundException|Throwable
     */
    public function deposit(AccountsPostRequest $request, AccountService $service)
    {
        $account = $request->user()->account ?? throw new AccountNotFoundException();
        $service->deposit($account, $request->validated('amount'));
        return (new AccountsPostResource($account->refresh()))
            ->additional(['message' => 'Depósito realizado com sucesso.']);;
    }

    /**
     * @param AccountsPostRequest $request
     * @param AccountService $service
     * @return AccountsPostResource
     * @throws AccountNotFoundException|Throwable
     */
    public function withdraw(AccountsPostRequest $request, AccountService $service)
    {
        $account = $request->user()->account ?? throw new AccountNotFoundException();
        $service->widthdraw($account, $request->validated('amount'));
        return (new AccountsPostResource($account->refresh()))
            ->additional(['message' => 'Saque realizado com sucesso.']);;
    }
}

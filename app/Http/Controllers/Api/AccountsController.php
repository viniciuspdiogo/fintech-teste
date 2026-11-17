<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AccountsPostResource;
use App\Models\Account;

class AccountsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $account = Account::all();
        return AccountsPostResource::collection($account);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AccountsPostResource $request)
    {
        $newAccount = Account::create($request->validated());
        return new AccountsPostResource($newAccount);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AccountsPostResource $request, Account $account)
    {
        $account->update($request->validated());
        return new AccountsPostResource($account);
    }
}

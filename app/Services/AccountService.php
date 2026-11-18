<?php

namespace App\Services;

use App\Enum\TransactionType;
use App\Exceptions\InsufficientFundsException;
use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Throwable;

class AccountService
{

    /**
     * @param Account $account
     * @param int $amount
     * @return Transaction
     * @throws Throwable
     */
    public function deposit(Account $account, int $amount): Transaction
    {
        return DB::transaction(function () use ($account, $amount) {
            $account = Account::lockForUpdate()->findOrFail($account->id);

            $newBalance = $account->balance + $amount;

            $account->update(['balance' => $newBalance]);

            return $account->transactions()->create([
                'type' => TransactionType::Deposit,
                'amount' => $amount,
                'balance_momentary' => $newBalance,
            ]);
        });
    }

    public function widthDraw(Account $account, int $amount): Transaction
    {
        return DB::transaction(function () use ($account, $amount) {
            $account = Account::lockForUpdate()->findOrFail($account->id);

            if($account->balance < $amount) {
                throw new InsufficientFundsException();
            }
            $newBalance = $account->balance - $amount;

            $account->update(['balance' => $newBalance]);

            return $account->transactions()->create([
                'type' => TransactionType::Withdraw,
                'amount' => $amount,
                'balance_momentary' => $newBalance,
            ]);
        });
    }
}

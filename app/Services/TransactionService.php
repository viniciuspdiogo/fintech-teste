<?php

namespace App\Services;

use App\Models\Account;
use Illuminate\Database\Eloquent\Collection;

class TransactionService
{
    public function listTransactionsByAccount(Account $account, bool $limit = false): Collection
    {
        return $account->transactions()
            ->latest()
            ->when($limit, fn($query) => $query->limit(5))
            ->get();
    }
}

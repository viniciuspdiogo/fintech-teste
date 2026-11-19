<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Account;
use Illuminate\Database\Eloquent\Collection;

class TransactionService
{
    /**
     * @param Account $account
     * @param bool $limit
     * @param string|null $startDate
     * @param string|null $endDate
     * @return Collection
     */
    public function listTransactionsByAccount(
        Account $account,
        bool    $limit = false,
        ?string $startDate,
        ?string $endDate
    ): Collection
    {
        $tz = config('app.timezone');
        $hasDateFilter = filled($startDate) || filled($endDate);
        return $account->transactions()
            ->latest()
            ->when(filled($startDate), function ($query) use ($startDate, $tz) {
                $start = Carbon::createFromFormat('Y-m-d', $startDate, $tz)->startOfDay();
                $query->where('created_at', '>=', $start);
            })
            ->when(filled($endDate), function ($query) use ($endDate, $tz) {
                $end = Carbon::createFromFormat('Y-m-d', $endDate, $tz)->endOfDay();
                $query->where('created_at', '<=', $end);
            })
            ->when($limit && !$hasDateFilter, fn($query) => $query->limit(5))
            ->get();
    }
}

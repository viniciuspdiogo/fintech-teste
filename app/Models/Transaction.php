<?php

namespace App\Models;

use App\Enum\TransactionTypeEnum;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable('account_id','type','amount','balance_momentary','created_at')]
class Transaction extends Model
{
    /**
     * @return string[]
     */
    protected function casts(): array
    {
        return [
            'type' => TransactionTypeEnum::class,
            'amount' => 'integer',
            'balance_momentary' => 'integer',
        ];
    }
}

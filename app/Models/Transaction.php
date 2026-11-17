<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable('account_id','type','amount','balance_momentary')]
class Transaction extends Model
{
    /**
     * @return string[]
     */
    protected function casts(): array
    {
        return [
            'amount' => 'integer',
            'balance_momentary' => 'integer',
        ];
    }


}

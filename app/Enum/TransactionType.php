<?php

namespace App\Enum;

enum TransactionType: string
{
    /**
     * Transação de déposito
     */
    case Deposit = 'deposit';

    /**
     * Transação de saque
     */
    case Withdraw = 'withdraw';
}

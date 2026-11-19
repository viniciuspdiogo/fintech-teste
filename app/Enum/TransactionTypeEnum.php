<?php

namespace App\Enum;

enum TransactionTypeEnum: string
{
    /**
     * Transação de déposito
     */
    case Deposit = 'deposit';

    /**
     * Transação de saque
     */
    case Withdraw = 'withdraw';

    /**
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::Deposit => 'Depósito',
            self::Withdraw => 'Saque',
        };
    }
}

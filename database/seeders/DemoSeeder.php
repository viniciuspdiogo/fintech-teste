<?php

namespace Database\Seeders;

use App\Enum\TransactionTypeEnum;
use App\Models\Account;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    /**
     * @throws \Throwable
     */
    public function run(): void
    {
        DB::transaction(function () {
            $user = User::firstOrCreate(
                ['email' => 'teste@exemplo.com'],
                ['name' => 'Usuário Teste', 'password' => Hash::make('123')],
            );

            $account = Account::firstOrCreate(
                ['user_id' => $user->id],
                ['balance' => 0],
            );

            if ($account->transactions()->exists()) {
                return;
            }

            $operations = [
                [TransactionTypeEnum::Deposit,  200000, 30],
                [TransactionTypeEnum::Withdraw,  50000, 25],
                [TransactionTypeEnum::Deposit,  120000, 20],
                [TransactionTypeEnum::Withdraw,  80000, 15],
                [TransactionTypeEnum::Withdraw,  35000, 10],
                [TransactionTypeEnum::Deposit,   60000,  5],
                [TransactionTypeEnum::Withdraw,  15000,  2],
                [TransactionTypeEnum::Deposit,   25000,  1],
            ];

            $balance = 0;

            foreach ($operations as [$type, $amount, $daysAgo]) {
                $balance += $type === TransactionTypeEnum::Deposit ? $amount : -$amount;
                $date = now()->subDays($daysAgo);

                $account->transactions()->forceCreate([
                    'type' => $type,
                    'amount' => $amount,
                    'balance_momentary' => $balance,
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
            }

            $account->forceFill(['balance' => $balance])->save();
        });
    }
}

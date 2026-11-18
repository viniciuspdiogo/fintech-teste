<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\User;
use App\Models\Account;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AccountTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     */
    public function test_user_can_deposit_in_account(): void
    {
        $user = User::factory()->has(Account::factory()->state(['balance' => 0]))->create();
        $payload = [
            'amount' => 100
        ];

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/accounts/deposit', $payload);
        $response->assertStatus(200);

        $account = $user->account->fresh();
        $this->assertEquals(100, $account->balance);

        $this->assertDatabaseHas('transactions', [
            'account_id' => $account->id,
            'type' => 'deposit',
            'amount' => 100,
            'balance_momentary' => 100
        ]);
    }

    /**
     * @return void
     */
    public function test_user_cannot_deposit_decimal_values()
    {
        $user = User::factory()->create();

        $payload = [
            'amount' => 10.50
        ];

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/accounts/deposit', $payload);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['amount']);

        $this->assertEquals(0, $user->account->fresh()->balance);
    }

    /**
     * @return void
     */
    public function test_user_cannot_deposit_amount_less_than_minimum()
    {
        $user = User::factory()->create();

        $payload = [
            'amount' => 0
        ];

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/accounts/deposit', $payload);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['amount']);
    }

    /**
     * @return void
     */
    public function test_user_can_withdraw_from_account()
    {
        $user = User::factory()->create();
        $user->account->update(['balance' => 50000]);
        $payload = ['amount' => 20000];

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/accounts/withdraw', $payload);

        $response->assertStatus(200);

        $this->assertEquals(30000, $user->account->fresh()->balance);

        $this->assertDatabaseHas('transactions', [
            'account_id' => $user->account->id,
            'type' => 'withdraw',
            'amount' => 20000,
            'balance_momentary' => 30000
        ]);
    }

    /**
     * @return void
     */
    public function test_user_cannot_withdraw_more_than_available_balance()
    {
        $user = User::factory()->create();
        $payload = [
            'amount' => 50000
        ];

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/accounts/withdraw', $payload);
        $response->assertStatus(422);

        $account = $user->account->fresh();
        $this->assertEquals(0, $account->balance);
        $this->assertDatabaseCount('transactions', 0);
    }
}

<?php

namespace Database\Factories;

use App\Models\Account;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;

/**
 * @extends Factory<Model>
 */
class TransactionsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sender_account_id' => fn () => Account::factory()->create()->id,
            'receiver_account_id' => fn () => Account::factory()->create()->id,
            'amount' => $this->faker->randomFloat(2, 0, 50000),
            'currency' => 'USD', // You can set the currency here
            'exchange_rate' => $this->faker->randomFloat(2, 0.5, 2), // Example exchange rate
        ];
    }
}

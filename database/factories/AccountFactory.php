<?php

namespace Database\Factories;

use App\Models\User;
use App\ValueObjects\Currency;
use App\ValueObjects\Money;
use Illuminate\Database\Eloquent\Factories\Factory;

class AccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $currency = new Currency('USD'); // Replace with your desired currency logic
        $balance = Money::create($this->faker->randomFloat(2, 0, 50000), $currency);

        return [
            'id' => $this->faker->uuid,
            'user_id' => User::factory(), // Use User::factory() to create a fake user
            'currency' => $currency->isoCode(),
            'balance' => $balance->getAmount(),
        ];
    }
}

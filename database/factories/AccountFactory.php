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
        // Define an array of currency ISO codes
        $currencies = ['EUR', 'USD', 'AZN'];

        // Randomly select a currency from the array
        $currencyCode = $this->faker->randomElement($currencies);

        // Create a Currency object based on the selected ISO code
        $currency = new Currency($currencyCode);

        // Generate a random balance for the account
        $balance = Money::create($this->faker->randomFloat(2, 0, 50000), $currency);

        return [
            'id' => $this->faker->uuid,
            'user_id' => User::factory(), // Use User::factory() to create a fake user
            'currency' => $currency->isoCode(),
            'balance' => $balance->getAmount(),
        ];
    }
}

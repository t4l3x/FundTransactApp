<?php
declare(strict_types=1);

namespace Tests\Unit\ValueObjects;
use Tests\TestCase;
use App\ValueObjects\Money;
use Money\Currency;
class MoneyTest extends TestCase
{
    /** @test */
    public function it_can_be_created()
    {
        $money = Money::create(100, new Currency('USD'));

        $this->assertInstanceOf(Money::class, $money);
    }

    /** @test */
    public function it_can_get_amount()
    {
        $money = Money::create(100, new Currency('USD'));

        $this->assertSame('100', $money->getAmount());
    }

    /** @test */
    public function it_can_get_currency()
    {
        $money = Money::create(100, new Currency('USD'));

        $this->assertInstanceOf(Currency::class, $money->getCurrency());
    }

    /** @test */
    public function it_can_add_money_to_another_money()
    {
        $money1 = Money::create(100, new Currency('USD'));
        $money2 = Money::create(50, new Currency('USD'));

        $result = $money1->add($money2);

        $this->assertSame('150', $result->getAmount());
    }

    /** @test */
    public function it_can_subtract_money_from_another_money()
    {
        $money1 = Money::create(100, new Currency('USD'));
        $money2 = Money::create(50, new Currency('USD'));

        $result = $money1->subtract($money2);

        $this->assertSame('50', $result->getAmount());
    }

    // Add more test cases as needed
}

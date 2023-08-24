<?php
declare(strict_types=1);

namespace Tests\Unit\ValueObjects;

use Tests\TestCase;
use App\ValueObjects\Currency;

class CurrencyTest extends TestCase
{
    /** @test */
    public function it_can_be_created()
    {
        $currency = new Currency('USD');

        $this->assertInstanceOf(Currency::class, $currency);
    }

    /** @test */
    public function it_can_get_iso_code()
    {
        $currency = new Currency('USD');

        $this->assertSame('USD', $currency->isoCode());
    }

    /** @test */
    public function it_can_check_equality_with_another_currency()
    {
        $currency1 = new Currency('USD');
        $currency2 = new Currency('USD');
        $currency3 = new Currency('EUR');

        $this->assertTrue($currency1->equals($currency2));
        $this->assertFalse($currency1->equals($currency3));
    }

    // Add more test cases as needed
}

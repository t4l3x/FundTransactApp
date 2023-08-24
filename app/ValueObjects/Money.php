<?php
declare(strict_types=1);

namespace App\ValueObjects;
use InvalidArgumentException;
use Money\Currency;
use Money\Money as MoneyPhpMoney;

class Money
{
    private MoneyPhpMoney $money;

    public function __construct(int|string $amount, Currency $currency)
    {
        $this->money = new MoneyPhpMoney($amount, $currency);
    }

    public static function create(int|string $amount, Currency $currency): self
    {
        return new self($amount, $currency);
    }

    public function getAmount(): string
    {
        return $this->money->getAmount();
    }

    public function getCurrency(): Currency
    {
        return $this->money->getCurrency();
    }

    public function add(self $other): self
    {
        $newMoney = $this->money->add($other->money);
        return new self($newMoney->getAmount(), $newMoney->getCurrency());
    }

    public function subtract(self $other): self
    {
        $newMoney = $this->money->subtract($other->money);
        return new self($newMoney->getAmount(), $newMoney->getCurrency());
    }
}

<?php
declare(strict_types=1);

namespace App\ValueObjects;


use Money\Money as MoneyPhpMoney;

class Money
{
    private MoneyPhpMoney $money;

    public function __construct(int|string $amount, Currency $currency)
    {
        $this->money = new MoneyPhpMoney($amount, $currency->getCurrency());
    }

    public static function create(int|string $amount, Currency $currency): self
    {
        return new self($amount, $currency);
    }

    public function getAmount(): string
    {
        return $this->money->getAmount();
    }

    public function toIntegerAmount(): int
    {
        // Convert decimal to integer representation
        return (int) bcmul($this->getAmount(), '100');
    }

    public function toDecimalAmount(): string
    {
        // Convert integer back to decimal
        return bcdiv($this->getAmount(), '100', 2); // Adjust scale as needed
    }

    public function getCurrency(): Currency
    {
        return new Currency($this->money->getCurrency()->getCode());
    }

    public function add(self $other): self
    {
        $newMoney = $this->money->add($other->money);
        return new self($newMoney->getAmount(), new Currency($newMoney->getCurrency()->getCode()));
    }

    public function subtract(self $other): self
    {
        $newMoney = $this->money->subtract($other->money);
        return new self($newMoney->getAmount(), new Currency($newMoney->getCurrency()->getCode()));
    }
    public function equals(self $other): bool
    {
        return $this->getAmount() === $other->getAmount() && $this->getCurrency() === $other->getCurrency();
    }
}

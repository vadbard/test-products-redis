<?php

declare(strict_types = 1);

namespace App\Value;

readonly class Money implements \JsonSerializable
{
    public function __construct(public int $amount)
    {
        if ($this->amount < 0) {
            throw new \InvalidArgumentException('Money amount must be positive');
        }
    }

    public static function fromFloat(float $amount): self
    {
        $amount = $amount * 100;

        return new self((int) $amount);
    }

    public function float() : float
    {
        return round($this->amount / 100, 2);
    }

    public function __toString(): string
    {
        return number_format($this->float(), 2, '.', '');
    }

    public function jsonSerialize(): float
    {
        return $this->float();
    }

    public function multiply(int $multiplier): self
    {
        return new self($this->amount * $multiplier);
    }

    public function add(Money $money): self
    {
        return new self($this->amount + $money->amount);
    }
}

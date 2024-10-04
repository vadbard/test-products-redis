<?php

declare(strict_types = 1);

namespace App\Value;

final readonly class LineItem
{
    public function __construct(
        private string $name,
        private int $quantity,
        private Money $price,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getPrice(): Money
    {
        return $this->price;
    }

    public function getTotal(): Money
    {
        return $this->price->multiply($this->quantity);
    }
}

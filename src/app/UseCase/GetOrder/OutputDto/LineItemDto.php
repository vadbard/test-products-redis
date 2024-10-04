<?php

namespace App\UseCase\GetOrder\OutputDto;

use App\Value\Money;

final readonly class LineItemDto
{
    public function __construct(
        public string $name,
        public int $quantity,
        public float $price,
    )
    {
    }
}

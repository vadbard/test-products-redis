<?php

declare(strict_types = 1);

namespace App\UseCase\GetOrder\OutputDto;

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

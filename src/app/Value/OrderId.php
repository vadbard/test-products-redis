<?php

declare(strict_types = 1);

namespace App\Value;

class OrderId
{
    public function __construct(
        public string $value,
    )
    {
    }

    public function __toString(): string
    {
        return $this->value;
    }
}

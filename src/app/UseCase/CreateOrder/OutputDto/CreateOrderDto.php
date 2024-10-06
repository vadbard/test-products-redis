<?php

declare(strict_types = 1);

namespace App\UseCase\CreateOrder\OutputDto;

use App\Value\OrderId;

final readonly class CreateOrderDto
{
    public function __construct(
        public OrderId $id,
    )
    {
    }
}

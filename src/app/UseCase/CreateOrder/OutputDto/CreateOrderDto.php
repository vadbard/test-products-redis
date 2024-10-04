<?php

namespace App\UseCase\CreateOrder\Dto;

use App\Value\OrderId;

final readonly class CreateOrderDto
{
    public function __construct(
        public string $id,
    )
    {
    }
}

<?php

namespace App\UseCase\GetOrder\InputDto;

use App\Value\OrderId;

final readonly class GetOrderInputDto
{
    public function __construct(
        public OrderId $orderId,
    )
    {
    }
}

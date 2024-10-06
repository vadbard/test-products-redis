<?php

declare(strict_types = 1);

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

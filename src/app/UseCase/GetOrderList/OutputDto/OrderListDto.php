<?php

namespace App\UseCase\GetOrderList\OutputDto;

final readonly class OrderListDto
{
    public function __construct(
        /** @var OrderListItemDto[] */
        public array $orders,
    )
    {
    }
}

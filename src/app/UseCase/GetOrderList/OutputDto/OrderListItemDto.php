<?php

declare(strict_types = 1);

namespace App\UseCase\GetOrderList\OutputDto;

readonly class OrderListItemDto
{
    public function __construct(
        public string $id,
        public string $createdAt,
        public int $itemsQuantity,
        public int $totalQuantity,
        public float $total,
    )
    {
    }
}

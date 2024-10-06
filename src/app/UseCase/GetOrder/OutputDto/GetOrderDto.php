<?php

declare(strict_types = 1);

namespace App\UseCase\GetOrder\OutputDto;

final readonly class GetOrderDto
{
    public function __construct(
        public string $id,
        public string $createdAt,
        public int $itemsQuantity,
        public int $totalQuantity,
        public float $total,

        /** @var LineItemDto[] */
        public array $items,
    )
    {
    }
}

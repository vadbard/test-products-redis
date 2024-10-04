<?php

namespace App\UseCase\CreateOrder\Dto;

use App\Value\LineItem;

final readonly class CreateOrderInputDto
{
    public function __construct(
        public \DateTimeImmutable $createdAt,

        /** @var LineItem[]  */
        public array $lineItems,
    )
    {
    }
}

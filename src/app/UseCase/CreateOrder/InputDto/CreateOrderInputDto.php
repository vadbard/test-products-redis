<?php

declare(strict_types = 1);

namespace App\UseCase\CreateOrder\InputDto;

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

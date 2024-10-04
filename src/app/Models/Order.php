<?php

declare(strict_types = 1);

namespace App\Models;

use App\Value\LineItem;
use App\Value\Money;
use App\Value\OrderId;
use DateTimeImmutable;

final class Order
{
    public function __construct(
        readonly private OrderId            $id,
        readonly private \DateTimeImmutable $createdAt,
        private array                       $lineItems = [],
    ) {
    }

    public function getId(): OrderId
    {
        return $this->id;
    }

    /**
     * @return LineItem[]
     */
    public function getLineItems(): array
    {
        return $this->lineItems;
    }

    public function addLineItem(LineItem $item): void
    {
        $this->lineItems[] = $item;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getTotalQuantity(): int
    {
        $total = 0;
        foreach ($this->lineItems as $item) {
            $total += $item->getQuantity();
        }

        return $total;
    }

    public function getItemsQuantity(): int
    {
        return count($this->lineItems);
    }

    public function getTotal(): Money
    {
        $total = new Money(0);
        foreach ($this->lineItems as $item) {
            $total = $total->add($item->getTotal());
        }

        return $total;
    }
}

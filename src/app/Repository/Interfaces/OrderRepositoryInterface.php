<?php

declare(strict_types = 1);

namespace App\Repository\Interfaces;

use App\Models\Order;
use App\Value\OrderId;

interface OrderRepositoryInterface
{
    public function save(Order $order): void;

    public function findOneById(OrderId $id): ?Order;

    public function findAll(): array;
}

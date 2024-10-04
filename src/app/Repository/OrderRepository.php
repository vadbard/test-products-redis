<?php

declare(strict_types = 1);

namespace App\Repository;

use App\Models\Order;
use App\Value\OrderId;
use Illuminate\Redis\RedisManager;

class OrderRepository
{
    public function __construct(
        private RedisManager $redis,
    )
    {
    }

    public function save(Order $order): void
    {
        $this->redis->client()->set($order->getId(), $order);
    }

    public function findOneById(OrderId $id): ?Order
    {
        $result = $this->redis->client()->get($id->value);

        if ($result === false) {
            return null;
        }

        return $result;
    }

    /**
     * @return Order[]
     */
    public function findAll(): array
    {
        $result = [];

        $keys = $this->redis->client()->keys('*');

        foreach ($keys as $key) {
            $result[] = $this->findOneById($key);
        }

        return $result;
    }
}

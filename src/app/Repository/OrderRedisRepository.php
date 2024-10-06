<?php

declare(strict_types = 1);

namespace App\Repository;

use App\Exceptions\Repository\RepositoryException;
use App\Models\Order;
use App\Repository\Interfaces\OrderRepositoryInterface;
use App\Value\OrderId;
use Illuminate\Redis\RedisManager;

final readonly class OrderRedisRepository implements OrderRepositoryInterface
{
    public function __construct(
        private RedisManager $redis,
    )
    {
    }

    /**
     * @throws RepositoryException
     */
    public function save(Order $order): void
    {
        try {
            $this->redis->client()->set($order->getId(), $order);
        } catch (\Throwable $e) {
            throw new RepositoryException('Не удалось сохранить заказ', RepositoryException::NOT_SAVED, $e);
        }
    }

    /**
     * @throws RepositoryException
     */
    public function findOneById(OrderId $id): ?Order
    {
        try {
            $result = $this->redis->client()->get($id->value);
        } catch (\Throwable $e) {
            throw new RepositoryException('Ошибка при получении заказа по id: ' . $id->value, RepositoryException::NOT_FOUND, $e);
        }

        if ($result === false) {
            return null;
        }

        return $result;
    }

    /**
     * @return Order[]
     * @throws RepositoryException
     */
    public function findAll(): array
    {
        $result = [];

        try {
            $keys = $this->redis->client()->keys('*');
        } catch (\Throwable $e) {
            throw new RepositoryException('Ошибка при получении всех ключей', RepositoryException::NOT_FOUND, $e);
        }

        foreach ($keys as $key) {
            $result[] = $this->findOneById($key);
        }

        return $result;
    }
}

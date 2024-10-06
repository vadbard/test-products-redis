<?php

declare(strict_types = 1);

namespace App\Repository;

use App\Exceptions\Repository\RepositoryException;
use App\Models\Order;
use App\Repository\Interfaces\OrderRepositoryInterface;
use App\Value\OrderId;
use Illuminate\Redis\RedisManager;
use WayOfDev\Serializer\SerializerManager;

class OrderRedisRepository implements OrderRepositoryInterface
{
    private const string CONNECTION_NAME = 'orders';

    public function __construct(
        private RedisManager $redis,
        private SerializerManager $serializer,
    )
    {
    }

    /**
     * @throws RepositoryException
     */
    public function save(Order $order): void
    {
        $serialized = $this->serializer->serialize($order);

        try {
            $this->redis->connection(self::CONNECTION_NAME)->client()->set($order->getId()->value, $serialized);
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
            $result = $this->redis->connection(self::CONNECTION_NAME)->client()->get($id->value);
        } catch (\Throwable $e) {
            throw new RepositoryException('Ошибка при получении заказа по id: ' . $id->value, RepositoryException::NOT_FOUND, $e);
        }

        if ($result === false) {
            return null;
        }

        try {
            $result = $this->serializer->unserialize($result, Order::class);
        } catch (\Throwable $e) {
            throw new RepositoryException('Ошибка при десериализации заказа по id: ' . $id->value, RepositoryException::INFRASTRUCTURE_ERROR, $e);
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
            $keys = $this->redis->connection(self::CONNECTION_NAME)->client()->keys('*');
        } catch (\Throwable $e) {
            throw new RepositoryException('Ошибка при получении всех ключей', RepositoryException::NOT_FOUND, $e);
        }

        foreach ($keys as $key) {
            $result[] = $this->findOneById(new OrderId($key));
        }

        return $result;
    }
}

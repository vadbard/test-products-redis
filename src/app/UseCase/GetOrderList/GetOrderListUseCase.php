<?php

declare(strict_types = 1);

namespace App\UseCase\GetOrderList;

use App\Exceptions\UseCase\UseCaseException;
use App\Repository\Interfaces\OrderRepositoryInterface;
use App\UseCase\GetOrderList\OutputDto\OrderListDto;
use App\UseCase\GetOrderList\OutputDto\OrderListItemDto;

final readonly class GetOrderListUseCase
{
    public function __construct(
        private OrderRepositoryInterface $orderRepository,
    )
    {
    }

    /**
     * @throws UseCaseException
     */
    public function execute(): OrderListDto
    {
        try {
            $orders = $this->orderRepository->findAll();
        } catch (\Throwable $e) {
            throw new UseCaseException('Ошибка при получении заказов', UseCaseException::CODE_SERVER_ERROR, $e);
        }

        $result = [];
        foreach ($orders as $order) {
            $result[] = new OrderListItemDto(
                id: $order->getId()->value,
                createdAt: $order->getCreatedAt()->format('Y-m-d H:i:s'),
                itemsQuantity: $order->getItemsQuantity(),
                totalQuantity: $order->getTotalQuantity(),
                total: $order->getTotal()->float(),
            );
        }

        return new OrderListDto(orders: $result);
    }
}

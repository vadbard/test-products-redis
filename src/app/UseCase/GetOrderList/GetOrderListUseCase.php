<?php

namespace App\UseCase\GetOrderList;

use App\Repository\OrderRepository;
use App\UseCase\GetOrderList\OutputDto\OrderListDto;
use App\UseCase\GetOrderList\OutputDto\OrderListItemDto;

class GetOrderListUseCase
{
    public function __construct(
        private readonly OrderRepository $orderRepository,
    )
    {
    }

    public function execute(): OrderListDto
    {
        $result = [];
        foreach ($this->orderRepository->findAll() as $order) {
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

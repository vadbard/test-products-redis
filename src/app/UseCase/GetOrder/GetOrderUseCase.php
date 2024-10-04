<?php

namespace App\UseCase\GetOrder;

use App\Exceptions\UseCase\UseCaseException;
use App\Repository\OrderRepository;
use App\UseCase\GetOrder\InputDto\GetOrderInputDto;
use App\UseCase\GetOrder\OutputDto\GetOrderDto;
use App\UseCase\GetOrder\OutputDto\LineItemDto;

class GetOrderUseCase
{
    public function __construct(
        private OrderRepository $orderRepository,
    )
    {
    }

    public function execute(GetOrderInputDto $dto): GetOrderDto
    {
        $order = $this->orderRepository->findOneById($dto->orderId);

        if (is_null($order)) {
            throw new UseCaseException('Order not found', 404, null);
        }

        $items = [];
        foreach ($order->getLineItems() as $item) {
            $items[] = new LineItemDto(
                name: $item->getName(),
                quantity: $item->getQuantity(),
                price: $item->getPrice()->float(),
            );
        }

        return new GetOrderDto(
            id: $order->getId()->value,
            createdAt: $order->getCreatedAt()->format('Y-m-d H:i:s'),
            itemsQuantity: $order->getItemsQuantity(),
            totalQuantity: $order->getTotalQuantity(),
            total: $order->getTotal()->float(),
            items: $items,
        );
    }
}

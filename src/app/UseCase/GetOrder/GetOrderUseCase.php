<?php

declare(strict_types = 1);

namespace App\UseCase\GetOrder;

use App\Exceptions\UseCase\UseCaseException;
use App\Repository\Interfaces\OrderRepositoryInterface;
use App\UseCase\GetOrder\InputDto\GetOrderInputDto;
use App\UseCase\GetOrder\OutputDto\GetOrderDto;
use App\UseCase\GetOrder\OutputDto\LineItemDto;

final readonly class GetOrderUseCase
{
    public function __construct(
        private OrderRepositoryInterface $orderRepository,
    )
    {
    }

    /**
     * @throws UseCaseException
     */
    public function execute(GetOrderInputDto $dto): GetOrderDto
    {
        try {
            $order = $this->orderRepository->findOneById($dto->orderId);
        } catch (\Throwable $e) {
            throw new UseCaseException('Ошибка при получении заказа', UseCaseException::CODE_SERVER_ERROR, $e);
        }

        if (is_null($order)) {
            throw new UseCaseException('Заказ не найден', UseCaseException::CODE_NOT_FOUND, null);
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

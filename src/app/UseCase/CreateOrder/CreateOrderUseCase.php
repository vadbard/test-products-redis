<?php

declare(strict_types = 1);

namespace App\UseCase\CreateOrder;

use App\Exceptions\UseCase\UseCaseException;
use App\Models\Order;
use App\Repository\Interfaces\OrderRepositoryInterface;
use App\UseCase\CreateOrder\InputDto\CreateOrderInputDto;
use App\UseCase\CreateOrder\OutputDto\CreateOrderDto;
use App\Value\OrderId;
use Illuminate\Support\Str;

final readonly class CreateOrderUseCase
{
    public function __construct(
        private OrderRepositoryInterface $orderRepository,
        private Str                      $str,
    )
    {
    }

    /**
     * @throws UseCaseException
     */
    public function execute(CreateOrderInputDto $dto): CreateOrderDto
    {
        $id = new OrderId($this->str->uuid()->toString());

        $order = new Order(
            id: $id,
            createdAt: $dto->createdAt,
            lineItems: $dto->lineItems,
        );

        try {
            $this->orderRepository->save($order);
        } catch (\Throwable $e) {
            throw new UseCaseException('Ошибка при сохранении заказа', UseCaseException::CODE_SERVER_ERROR, $e);
        }

        return new CreateOrderDto(id: $id);
    }
}

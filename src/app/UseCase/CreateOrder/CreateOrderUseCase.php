<?php

namespace App\UseCase\CreateOrder;

use App\Models\Order;
use App\Repository\OrderRepository;
use App\UseCase\CreateOrder\Dto\CreateOrderDto;
use App\UseCase\CreateOrder\Dto\CreateOrderInputDto;
use App\Value\OrderId;
use Illuminate\Support\Str;

final readonly class CreateOrderUseCase
{
    public function __construct(
        private OrderRepository $orderRepository,
        private Str $str,
    )
    {
    }

    public function execute(CreateOrderInputDto $dto): CreateOrderDto
    {
        $id = new OrderId($this->str->uuid()->toString());

        $order = new Order(
            id: $id,
            createdAt: $dto->createdAt,
            lineItems: $dto->lineItems,
        );

        $this->orderRepository->save($order);

        return new CreateOrderDto($id->value);
    }
}

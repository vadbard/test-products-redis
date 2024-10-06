<?php

declare(strict_types = 1);

namespace App\UseCase\CreateOrder;

use App\Exceptions\UseCase\AbstractUseCaseException;
use App\Exceptions\UseCase\CreateOrder\DuplicateProductsCheckFailedCreateOrderUseCaseException;
use App\Exceptions\UseCase\CreateOrder\OrderSaveErrorCreateOrderUseCaseException;
use App\Exceptions\UseCase\CreateOrder\ProductsCountCheckFailedCreateOrderUseCaseException;
use App\Models\Order;
use App\Repository\Interfaces\OrderRepositoryInterface;
use App\UseCase\CreateOrder\InputDto\CreateOrderInputDto;
use App\UseCase\CreateOrder\OutputDto\CreateOrderDto;
use App\Value\OrderId;
use Carbon\CarbonPeriodImmutable;
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
     * @throws AbstractUseCaseException
     */
    public function execute(CreateOrderInputDto $dto): CreateOrderDto
    {
        $id = new OrderId($this->str->uuid()->toString());

        $order = new Order(
            id: $id,
            createdAt: $dto->createdAt,
            lineItems: $dto->lineItems,
        );

        $this->checkProductsCount($order);

        $this->checkDuplicateItems($order);

        try {
            $this->orderRepository->save($order);
        } catch (\Throwable $e) {
            throw new OrderSaveErrorCreateOrderUseCaseException($e);
        }

        return new CreateOrderDto(id: $id);
    }

    private function checkProductsCount(Order $order): void
    {
        $period = CarbonPeriodImmutable::create('10.09.2024', '10.10.2024');

        if ($order->getTotalQuantity() > 10 && $period->contains($order->getCreatedAt())) {
            throw new ProductsCountCheckFailedCreateOrderUseCaseException();
        }
    }

    private function checkDuplicateItems(Order $order): void
    {
        $seen = [];
        foreach ($order->getLineItems() as $item) {
            $key = $item->getName() . $item->getPrice();

            if (isset($seen[$key])) {
                throw new DuplicateProductsCheckFailedCreateOrderUseCaseException();
            } else {
                $seen[$key] = true;
            }
        }
    }
}

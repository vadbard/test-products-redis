<?php

declare(strict_types = 1);

namespace App\UseCase\CreateOrder;

use App\Exceptions\UseCase\UseCaseException;
use App\Models\Order;
use App\Repository\Interfaces\OrderRepositoryInterface;
use App\UseCase\CreateOrder\InputDto\CreateOrderInputDto;
use App\UseCase\CreateOrder\OutputDto\CreateOrderDto;
use App\Value\OrderId;
use Carbon\CarbonPeriodImmutable;
use Illuminate\Support\Carbon;
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

        $this->checkProductsCount($order);

        $this->checkDuplicateItems($order);

        try {
            $this->orderRepository->save($order);
        } catch (\Throwable $e) {
            throw new UseCaseException('Ошибка при сохранении заказа', UseCaseException::CODE_SERVER_ERROR, $e);
        }

        return new CreateOrderDto(id: $id);
    }

    private function checkProductsCount(Order $order): void
    {
        $period = CarbonPeriodImmutable::create('10.09.2024', '10.10.2024');

        if ($order->getTotalQuantity() > 10 && $period->contains($order->getCreatedAt())) {
            throw new UseCaseException('Суммарное количество всех единиц не должно превышать 10 в период с 10.09.2024 по 10.10.2024');
        }
    }

    private function checkDuplicateItems(Order $order): void
    {
        $seen = [];
        foreach ($order->getLineItems() as $item) {
            $key = $item->getName() . $item->getPrice();

            if (isset($seen[$key])) {
                throw new UseCaseException('Дублирующиеся товары недопустимы');
            } else {
                $seen[$key] = true;
            }
        }
    }
}

<?php

namespace App\Console\Commands;

use App\UseCase\GetOrderList\GetOrderListUseCase;
use App\UseCase\GetOrderList\OutputDto\OrderListDto;
use Illuminate\Console\Command;
use function Laravel\Prompts\table;

class ListOrdersCommand extends Command
{
    protected $signature = 'order:list';

    public function handle(GetOrderListUseCase $useCase)
    {
        $dto = $useCase->execute();

        $this->view($dto);
    }

    private function view(OrderListDto $dto): void
    {
        $items = [];

        foreach ($dto->orders as $order) {
            $items[] = [
                $order->id,
                $order->createdAt,
                $order->itemsQuantity,
                $order->totalQuantity,
                $order->total,
            ];
        }

        table(
            ['ID', 'Дата создания', 'Кол-во товаров', 'Сумма единиц товаров', 'Общая стоимость'],
            $items
        );
    }
}

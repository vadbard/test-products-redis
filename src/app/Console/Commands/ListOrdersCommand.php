<?php

declare(strict_types = 1);

namespace App\Console\Commands;

use App\Console\Commands\Traits\ConsoleErrorTrait;
use App\Exceptions\UseCase\UseCaseException;
use App\UseCase\GetOrderList\GetOrderListUseCase;
use App\UseCase\GetOrderList\OutputDto\OrderListDto;
use Illuminate\Console\Command;
use function Laravel\Prompts\table;

class ListOrdersCommand extends Command
{
    use ConsoleErrorTrait;

    protected $signature = 'order:list';

    public function handle(GetOrderListUseCase $useCase)
    {
        try {
            $dto = $useCase->execute();
        } catch (UseCaseException $e) {
            $this->useCaseException($e);
        } catch (\Exception $e) {
            $this->unknownException($e);
        }

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

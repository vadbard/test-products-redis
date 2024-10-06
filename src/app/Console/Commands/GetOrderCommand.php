<?php

declare(strict_types = 1);

namespace App\Console\Commands;

use App\Console\Commands\Traits\ConsoleErrorTrait;
use App\Exceptions\UseCase\UseCaseException;
use App\UseCase\GetOrder\GetOrderUseCase;
use App\UseCase\GetOrder\InputDto\GetOrderInputDto;
use App\UseCase\GetOrder\OutputDto\GetOrderDto;
use App\Value\OrderId;
use Illuminate\Console\Command;
use function Laravel\Prompts\table;

class GetOrderCommand extends Command
{
    use ConsoleErrorTrait;

    protected $signature = 'order:show {orderId}';

    public function handle(GetOrderUseCase $useCase): void
    {
        try {
            $dto = $useCase->execute(new GetOrderInputDto(
                orderId: $this->parseOrderId($this->argument('orderId')),
            ));
        } catch (UseCaseException $e) {
            $this->useCaseException($e);
        } catch (\Exception $e) {
            $this->unknownException($e);
        }

        $this->view($dto);
    }

    private function view(GetOrderDto $dto): void
    {
        $this->info('ID: '. $dto->id);
        $this->info('Дата создания: '. $dto->createdAt);
        $this->info('Количество товаров: '. $dto->itemsQuantity);
        $this->info('Общее количество товаров: '. $dto->totalQuantity);
        $this->info('Сумма: '. $dto->total);

        $items = [];
        foreach ($dto->items as $item) {
            $items[] = [
                $item->name,
                $item->price,
                $item->quantity,
            ];
        }

        table(
            ['Наименование товара', 'Цена за единицу', 'Количество единиц товаров'],
            $items,
        );
    }

    private function parseOrderId(string $id): OrderId
    {
        return new OrderId($id);
    }
}

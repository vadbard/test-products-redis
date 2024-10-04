<?php

namespace App\Console\Commands;

use App\Exceptions\UseCase\UseCaseException;
use App\UseCase\GetOrder\GetOrderUseCase;
use App\UseCase\GetOrder\InputDto\GetOrderInputDto;
use App\UseCase\GetOrder\OutputDto\GetOrderDto;
use App\Value\OrderId;
use Illuminate\Console\Command;
use function Laravel\Prompts\table;

class GetOrderCommand extends Command
{
    protected $signature = 'order:show {orderId}';

    public function handle(GetOrderUseCase $useCase)
    {
        $orderId = $this->argument('orderId');

        try {
            $dto = $useCase->execute(new GetOrderInputDto(
                orderId: new OrderId($orderId),
            ));

            $this->view($dto);
        } catch (UseCaseException $e) {
            $this->error($e->getMessage());
        } catch (\Exception $e) {
            $this->error('Произошла странная ошибка');
        }
    }

    private function view(GetOrderDto $dto): void
    {
        $this->info('ID: '. $dto->id);
        $this->info('Дата создания: '. $dto->createdAt);
        $this->info('Количество товаров: '. $dto->itemsQuantity);
        $this->info('Общее количество товаров: '. $dto->totalQuantity);
        $this->info('Сумма: '. $dto->total);

        foreach ($dto->items as $item) {
            table(
                ['Наименование товара', 'Цена за единицу', 'Количество единиц товаров'],
                collect([
                    $item->name,
                    $item->price,
                    $item->quantity,
                ])
            );
        }
    }
}

<?php

namespace App\Console\Commands;

use App\Exceptions\UseCase\UseCaseException;
use App\UseCase\CreateOrder\CreateOrderUseCase;
use App\UseCase\CreateOrder\Dto\CreateOrderDto;
use App\UseCase\CreateOrder\Dto\CreateOrderInputDto;
use App\Value\LineItem;
use App\Value\Money;
use Illuminate\Console\Command;

class CreateOrderCommand extends Command
{
    protected $signature = 'order:create
                            {--created_at= : The creation date of the order}
                            {--product= : The product details in the format name:unit_price:quantity}';

    public function handle(CreateOrderUseCase $useCase)
    {
        $createdAt = $this->option('created_at');
        $products = $this->option('product');

        if (! $createdAt) {
            $this->error('The --created_at option is required.');
            return;
        }

        $lineItems = [];
        if ($products) {
            $productList = explode(',', $products);
            foreach ($productList as $product) {
                list($name, $unitPrice, $quantity) = explode(':', $product);

                $lineItems[] = new LineItem(
                    name: $name,
                    quantity: $quantity,
                    price: Money::fromFloat($unitPrice),
                );
            }
        } else {
            $this->error('At least one product must be specified using the --product option.');
        }

        try {
            $dto = $useCase->execute(new CreateOrderInputDto(
                createdAt: new \DateTimeImmutable($createdAt),
                lineItems: $lineItems,
            ));

            $this->view($dto);
        } catch (UseCaseException $e) {
            $this->error($e->getMessage());
        } catch (\Exception $e) {
            $this->error('Произошла странная ошибка');
        }
    }

    private function view(CreateOrderDto $dto): void
    {
        $this->info("Создан заказ с id: $dto->id");
    }
}

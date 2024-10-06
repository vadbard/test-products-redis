<?php

declare(strict_types = 1);

namespace App\Console\Commands;

use App\Console\Commands\Traits\ConsoleErrorTrait;
use App\Exceptions\UseCase\AbstractUseCaseException;
use App\UseCase\CreateOrder\CreateOrderUseCase;
use App\UseCase\CreateOrder\InputDto\CreateOrderInputDto;
use App\UseCase\CreateOrder\OutputDto\CreateOrderDto;
use App\Value\LineItem;
use App\Value\Money;
use DateTimeImmutable;
use Illuminate\Console\Command;

class CreateOrderCommand extends Command
{
    use ConsoleErrorTrait;

    protected $signature = 'order:create
                            {--created_at= : Дата создания заказа в формате Y.m.d H:i:s}
                            {--product=* : Товар в формате название:цена:количество}';

    public function handle(CreateOrderUseCase $useCase)
    {
        $createdAt = $this->parseCreatedAt($this->option('created_at'));
        $products = $this->parseProducts($this->option('product'));

        $lineItems = [];
        foreach ($products as $key => $product) {
            $data = $this->parseProduct($product, $key);

            list($name, $unitPrice, $quantity) = $data;

            $lineItems[] = new LineItem(
                name: $name,
                quantity: $quantity,
                price: Money::fromFloat($unitPrice),
            );
        }

//        try {
            $dto = $useCase->execute(new CreateOrderInputDto(
                createdAt: $createdAt,
                lineItems: $lineItems,
            ));
//        } catch (AbstractUseCaseException $e) {
//            $this->useCaseException($e);
//        } catch (\Exception $e) {
//            $this->unknownException($e);
//        }

        $this->view($dto);
    }

    private function view(CreateOrderDto $dto): void
    {
        $this->info("Создан заказ с id: $dto->id");
    }

    private function parseCreatedAt(string $createdAt): DateTimeImmutable
    {
        if ($createdAt === '') {
            $this->inputError('Опция --created_at является обязательной.');
        }

        $createdAt = \DateTimeImmutable::createFromFormat('d.m.Y H:i:s', $createdAt);

        if ($createdAt === false) {
            $this->inputError('Формат даты не верен.');
        }

        return $createdAt;
    }

    private function parseProducts(array|string $products): array
    {
        if (empty($products)) {
            $this->inputError('Должен быть указан хотя бы один продукт с помощью опции --product.');
        }

        if (is_string($products)) {
            $products = [$products];
        }

        return $products;
    }

    private function parseProduct(string $productData, int $counter): array
    {
        $data = explode(':', $productData);

        if (count($data) !== 3) {
            $this->inputError("Формат опции --product элемента $counter не верный");
        }

        list($name, $unitPrice, $quantity) = $data;

        if (empty($name)) {
            $this->inputError("Формат опции --product элемента $counter не верный: название не должно быть пустым");
        }

        if (! is_numeric($unitPrice)) {
            $this->inputError("Формат опции --product элемента $counter не верный: цена должна быть числом");
        }

        if (! is_numeric($quantity)) {
            $this->inputError("Формат опции --product элемента $counter не верный: количество должно быть числом");
        }

        return [$name, (float) $unitPrice, (int) $quantity];
    }
}

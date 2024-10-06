<?php

namespace Feature;

use App\Exceptions\UseCase\CreateOrder\DuplicateProductsCheckFailedCreateOrderUseCaseException;
use App\Exceptions\UseCase\CreateOrder\ProductsCountCheckFailedCreateOrderUseCaseException;
use App\Repository\Interfaces\OrderRepositoryInterface;
use App\Repository\OrderRedisRepository;
use App\UseCase\CreateOrder\CreateOrderUseCase;
use App\UseCase\CreateOrder\InputDto\CreateOrderInputDto;
use App\Value\LineItem;
use App\Value\Money;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class CreateOrderUseCaseTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->instance(
            OrderRepositoryInterface::class,
            Mockery::mock(OrderRedisRepository::class, function (MockInterface $mock) {
                $mock->shouldReceive('save');
            })
        );
    }

    /**
     * A basic test example.
     */
    public function testOk(): void
    {
        /** @var CreateOrderUseCase $useCase */
        $useCase = $this->app->make(CreateOrderUseCase::class);

        $inputDto = new CreateOrderInputDto(
            createdAt: new \DateTimeImmutable('2021-05-20 10:00:00'),
            lineItems: $this->makeLineItems(10, true),
        );

        $dto = $useCase->execute($inputDto);

        $this->assertNotEmpty($dto);
        $this->assertIsString($dto->id->value);
    }

    public function testProductCountCheck(): void
    {
        /** @var CreateOrderUseCase $useCase */
        $useCase = $this->app->make(CreateOrderUseCase::class);

        $inputDto = new CreateOrderInputDto(
            createdAt: new \DateTimeImmutable('2024-09-10 10:00:00'),
            lineItems: $this->makeLineItems(11, true),
        );

        $this->expectException(ProductsCountCheckFailedCreateOrderUseCaseException::class);
        $dto = $useCase->execute($inputDto);
    }


    public function testDuplicateLineItemsCheck(): void
    {
        /** @var CreateOrderUseCase $useCase */
        $useCase = $this->app->make(CreateOrderUseCase::class);

        $inputDto = new CreateOrderInputDto(
            createdAt: new \DateTimeImmutable('2024-12-10 10:00:00'),
            lineItems: $this->makeLineItems(11, false),
        );

        $this->expectException(DuplicateProductsCheckFailedCreateOrderUseCaseException::class);
        $dto = $useCase->execute($inputDto);
    }


    private function makeLineItems(int $count, bool $nameDifferent): array
    {
        $result = [];

        for ($i = 0; $i < $count; $i++) {
            $name = $nameDifferent? 'a' . $i : 'b';

            $result[] = new LineItem(name: $name, quantity: 1000, price: new Money(10000));
        }

        return $result;
    }
}

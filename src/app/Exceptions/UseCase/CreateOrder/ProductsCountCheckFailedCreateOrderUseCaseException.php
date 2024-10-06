<?php

declare(strict_types = 1);

namespace App\Exceptions\UseCase\CreateOrder;

use App\Exceptions\UseCase\AbstractUseCaseException;

class ProductsCountCheckFailedCreateOrderUseCaseException extends AbstractUseCaseException
{
    public const string MESSAGE = 'Суммарное количество всех единиц не должно превышать 10 в период с 10.09.2024 по 10.10.2024';
    public const int CODE = self::CODE_UNPROCESSABLE_ENTITY;
}

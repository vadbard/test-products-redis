<?php

declare(strict_types = 1);

namespace App\Exceptions\UseCase\GetOrder;

use App\Exceptions\UseCase\AbstractUseCaseException;

class OrderNotFoundGetOrderUseCaseException extends AbstractUseCaseException
{
    public const string MESSAGE = 'Заказ не найден';
    public const int CODE = self::CODE_NOT_FOUND;
}

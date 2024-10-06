<?php

declare(strict_types = 1);

namespace App\Exceptions\UseCase\CreateOrder;

use App\Exceptions\UseCase\AbstractUseCaseException;

class OrderSaveErrorCreateOrderUseCaseException extends AbstractUseCaseException
{
    public const string MESSAGE = 'Ошибка при сохранении заказа';
    public const int CODE = self::CODE_SERVER_ERROR;
}

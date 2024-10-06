<?php

declare(strict_types = 1);

namespace App\Exceptions\UseCase\GetOrder;

use App\Exceptions\UseCase\AbstractUseCaseException;

class OrderGetErrorGetOrderUseCaseException extends AbstractUseCaseException
{
    public const string MESSAGE = 'Ошибка получения заказа по id';
    public const int CODE = self::CODE_SERVER_ERROR;
}

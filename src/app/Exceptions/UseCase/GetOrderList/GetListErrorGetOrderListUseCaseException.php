<?php

declare(strict_types = 1);

namespace App\Exceptions\UseCase\GetOrderList;

use App\Exceptions\UseCase\AbstractUseCaseException;

class GetListErrorGetOrderListUseCaseException extends AbstractUseCaseException
{
    public const string MESSAGE = 'Ошибка при получении заказов';
    public const int CODE = self::CODE_SERVER_ERROR;
}

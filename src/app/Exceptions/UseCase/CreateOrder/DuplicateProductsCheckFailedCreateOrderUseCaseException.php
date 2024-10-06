<?php

declare(strict_types = 1);

namespace App\Exceptions\UseCase\CreateOrder;

use App\Exceptions\UseCase\AbstractUseCaseException;

class DuplicateProductsCheckFailedCreateOrderUseCaseException extends AbstractUseCaseException
{
    public const string MESSAGE = 'Дублирующиеся товары недопустимы';
    public const int CODE = self::CODE_UNPROCESSABLE_ENTITY;
}

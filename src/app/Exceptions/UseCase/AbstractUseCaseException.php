<?php

namespace App\Exceptions\UseCase;

abstract class AbstractUseCaseException extends \Exception
{
    public const int CODE_NOT_FOUND = 404;
    public const int CODE_UNPROCESSABLE_ENTITY = 422;
    public const int CODE_SERVER_ERROR = 500;

    public function __construct(\Throwable $previous = null)
    {
        parent::__construct(static::MESSAGE, static::CODE, $previous);
    }
}

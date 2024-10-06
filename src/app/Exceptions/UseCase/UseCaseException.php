<?php

declare(strict_types = 1);

namespace App\Exceptions\UseCase;

class UseCaseException extends \Exception
{
    public const int CODE_NOT_FOUND = 404;
    public const int CODE_SERVER_ERROR = 500;

    public function __construct(string $message = '', int $code = 0, \Throwable $previous = null,
                                public array $context = [])
    {
        parent::__construct($message, $code, $previous);
    }
}

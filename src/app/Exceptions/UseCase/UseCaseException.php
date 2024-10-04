<?php

declare(strict_types = 1);

namespace App\Exceptions\UseCase;

class UseCaseException extends \Exception
{
    public function __construct(string $message = '', int $code = 0, \Throwable $previous = null,
                                public array $context = [])
    {
        parent::__construct($message, $code, $previous);
    }
}

<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Exception\Infrastructure;

class DataStorageException extends \Exception
{
    public function __construct(string $message = '', int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

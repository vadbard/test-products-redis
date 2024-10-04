<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Exception\Repository;

class RepositoryException extends \Exception
{
    const NOT_FOUND = 1001;
    const NOT_SAVED = 1002;

    public function __construct(string $message = '', int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

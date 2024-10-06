<?php

declare(strict_types = 1);

namespace App\Exceptions\Repository;

class RepositoryException extends \Exception
{
    const int NOT_FOUND = 1001;
    const int NOT_SAVED = 1002;
    const int INFRASTRUCTURE_ERROR = 1003;

    public function __construct(string $message = '', int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

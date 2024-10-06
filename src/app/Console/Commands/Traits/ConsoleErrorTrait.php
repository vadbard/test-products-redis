<?php

namespace App\Console\Commands\Traits;

use App\Exceptions\UseCase\UseCaseException;

trait ConsoleErrorTrait
{
    private function useCaseException(UseCaseException $e): never
    {
        if ($e->getCode() >= 500) {
            $this->error('Произошла странная ошибка');
        } else {
            $this->error($e->getMessage());
        }

        exit(1);
    }

    private function unknownException(\Throwable $e): never
    {
        $this->error($e->getMessage());

        exit(1);
    }

    private function inputError(string $message): never
    {
        $this->error($message);

        exit(1);
    }
}

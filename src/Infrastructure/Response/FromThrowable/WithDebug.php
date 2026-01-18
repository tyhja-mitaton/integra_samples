<?php

declare(strict_types=1);

namespace Integra\Infrastructure\Response\FromThrowable;

use Integra\Infrastructure\Response\FromThrowable;

class WithDebug extends FromThrowable
{
    public function payload(): array
    {
        return
            [
                'message' => $this->throwable->getMessage(),
                'file' => $this->throwable->getFile(),
                'line' => $this->throwable->getLine(),
                'trace' => $this->throwable->gettrace(),
            ];
    }
}

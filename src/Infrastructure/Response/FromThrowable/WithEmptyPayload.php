<?php

declare(strict_types=1);

namespace Integra\Infrastructure\Response\FromThrowable;

use Integra\Infrastructure\Response\FromThrowable;

class WithEmptyPayload extends FromThrowable
{
    public function payload(): array
    {
        return [];
    }
}

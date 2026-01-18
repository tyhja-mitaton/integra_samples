<?php

namespace Integra\Infrastructure\Generic\Response;

class NotFound extends FailedWithOptionalError
{
    public function code(): int
    {
        return 404;
    }

    public function defaultErrorText(): string
    {
        return 'Not Found';
    }
}

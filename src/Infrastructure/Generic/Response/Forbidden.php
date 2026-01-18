<?php

namespace Integra\Infrastructure\Generic\Response;

class Forbidden extends FailedWithOptionalError
{
    public function code(): int
    {
        return 403;
    }

    public function defaultErrorText(): string
    {
        return 'Forbidden';
    }
}

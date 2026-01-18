<?php

namespace Integra\Infrastructure\Generic\Response;

class BadRequest extends FailedWithOptionalError
{
    public function code(): int
    {
        return 400;
    }

    public function defaultErrorText(): string
    {
        return 'Bad Request';
    }
}

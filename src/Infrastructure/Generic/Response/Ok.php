<?php

namespace Integra\Infrastructure\Generic\Response;

class Ok extends Successful
{
    public function code(): int
    {
        return 200;
    }

    public function defaultText(): string
    {
        return 'OK';
    }
}

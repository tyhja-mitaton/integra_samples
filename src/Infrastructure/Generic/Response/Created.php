<?php

namespace Integra\Infrastructure\Generic\Response;

class Created extends Successful
{
    public function code(): int
    {
        return 201;
    }

    public function defaultText(): string
    {
        return 'Created';
    }
}

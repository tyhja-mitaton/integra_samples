<?php

declare(strict_types=1);

namespace Integra\Infrastructure\Console\Message;

use Integra\Infrastructure\Console\Message;
use Integra\Infrastructure\Generic\Result;

class FromResult implements Message
{
    private Message $message;

    public function __construct(Result $result)
    {
        if ($result->isSuccessful()) {
            $this->message = new SuccessfulMessage($result->value());
        } else {
            $this->message = new ErrorMessage($result->error());
        }
    }

    public function value(): string
    {
        return $this->message->value();
    }
}

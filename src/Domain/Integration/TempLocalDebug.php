<?php

namespace Integra\Domain\Integration;
final class TempLocalDebug
{
    public static function echo(string $message): void
    {
        echo $message . PHP_EOL;
        fflush(STDOUT);
    }
}
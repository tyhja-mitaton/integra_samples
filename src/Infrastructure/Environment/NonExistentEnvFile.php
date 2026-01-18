<?php

declare(strict_types=1);

namespace Integra\Infrastructure\Environment;

use Dotenv\Dotenv;

class NonExistentEnvFile extends Dotenv
{
    public function __construct()
    {
        // nothing to construct
    }

    public function load()
    {
        // nothing to load
    }
}

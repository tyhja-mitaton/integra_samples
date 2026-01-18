<?php

declare(strict_types=1);

namespace Integra\Infrastructure\Application\System;

use Integra\Infrastructure\Application\System;

class ApiV1 implements System
{
    public function name(): string
    {
        return 'Api';
    }

    public function controllerNamespace(): string
    {
        return '\Integra\Controller\Api\V1';
    }
}

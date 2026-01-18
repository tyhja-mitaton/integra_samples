<?php

declare(strict_types=1);

namespace Integra\Infrastructure\Application\System;

use Integra\Infrastructure\Application\System;

class Admin implements System
{
    public function name(): string
    {
        return 'Admin';
    }

    public function controllerNamespace(): string
    {
        return 'Integra\Controller\Admin';
    }
}

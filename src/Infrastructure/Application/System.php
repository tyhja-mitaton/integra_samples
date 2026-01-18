<?php

declare(strict_types=1);

namespace Integra\Infrastructure\Application;

interface System
{
    public function name(): string;
    public function controllerNamespace(): string;
}

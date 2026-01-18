<?php

declare(strict_types=1);

namespace Integra\Infrastructure\Identity;

interface Specification
{
    public function isSatisfied(): bool;
}

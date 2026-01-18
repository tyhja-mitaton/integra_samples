<?php
declare(strict_types=1);

namespace Integra\Domain\Integration\Common;

interface DTOInterface
{
    public function toArray(): array;
}

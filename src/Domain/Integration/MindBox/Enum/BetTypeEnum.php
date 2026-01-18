<?php

namespace Integra\Domain\Integration\MindBox\Enum;

use Integra\Domain\Enum\EnumInterface;

enum BetTypeEnum:string implements EnumInterface
{
    case ORDINAR = '1';
    case SYSTEM = '2';
    case EXPRESS = '3';

    public function value(): string
    {
        return $this->value;
    }

    public function mindboxName(): string
    {
        return match ($this) {
            self::ORDINAR => 'Ordinar',
            self::SYSTEM => 'System',
            self::EXPRESS => 'Express',
        };
    }
}

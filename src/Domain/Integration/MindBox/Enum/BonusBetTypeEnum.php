<?php

namespace Integra\Domain\Integration\MindBox\Enum;

use Integra\Domain\Enum\EnumInterface;

enum BonusBetTypeEnum:string implements EnumInterface
{
    case ORDINAR_EXPRESS = '1';
    case ORDINAR = '2';
    case EXPRESS = '3';

    public function value(): string
    {
        return $this->value;
    }

    public function typeName(): string
    {
        return match ($this) {
            self::ORDINAR_EXPRESS => 'Ординар, экспресс',
            self::ORDINAR => 'Ординар',
            self::EXPRESS => 'Экспресс',
        };
    }
}

<?php

namespace Integra\Domain\Integration\MindBox\Enum;

use Integra\Domain\Enum\EnumInterface;

enum BonusTypeEnum:string implements EnumInterface
{
    case WAGER = '1';
    case FREE_BET = '2';

    public function value(): string
    {
        return $this->value;
    }

    public function typeName(): string
    {
        return match ($this) {
            self::WAGER => 'Wager',
            self::FREE_BET => 'FreeBet',
        };
    }
}

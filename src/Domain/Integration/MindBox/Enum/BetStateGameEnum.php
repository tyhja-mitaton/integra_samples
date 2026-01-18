<?php

namespace Integra\Domain\Integration\MindBox\Enum;

use Integra\Domain\Enum\EnumInterface;

enum BetStateGameEnum:string implements EnumInterface
{
    case IN_GAME = '4';
    case WIN = '1';
    case LOSE = '3';
    case CANCELED = '2';

    public function value(): string
    {
        return $this->value;
    }

    public function stateName():string
    {
        return match ($this) {
            self::IN_GAME => 'в игре',
            self::WIN => 'выигрыш',
            self::LOSE => 'проигрыш',
            self::CANCELED => 'отменена',
        };
    }
}

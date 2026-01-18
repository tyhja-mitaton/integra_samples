<?php

namespace Integra\Domain\Integration\MindBox\Enum;

use Integra\Domain\Enum\EnumInterface;

enum BetStateEnum:string implements EnumInterface
{
    case IN_GAME = '1';
    case WIN = '2';
    case LOSE = '3';
    case CANCELED = '4';
    case CASHOUT = '5';
    case CASHBACK = '6';
    case EXPRESS_BONUS = '7';
    case BORE_DRAW_MONEY_BACK = '10';
    case ULTRA_CASHBACK = '11';
    case DAY_EXPRESS = '12';

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
            self::CASHOUT => 'cashout',
            self::CASHBACK => 'cashback',
            self::EXPRESS_BONUS => 'expressBonus',
            self::BORE_DRAW_MONEY_BACK => 'BoreDrawMoneyBack',
            self::ULTRA_CASHBACK => 'ultra cashback',
            self::DAY_EXPRESS => 'экспресс дня',
        };
    }
}

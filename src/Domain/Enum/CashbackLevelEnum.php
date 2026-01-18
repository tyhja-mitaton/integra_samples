<?php
declare(strict_types=1);

namespace Integra\Domain\Enum;

/**
 * Уровень кэшбэка
 */
enum CashbackLevelEnum: string implements EnumInterface
{
    case PLAYER = '1';
    case BRONZE = '2';
    case SILVER = '3';
    case GOLD = '4';
    case PLATINUM = '5';

    public function value(): string
    {
        return $this->value;
    }

    public function label(): string
    {
        return match ($this) {
            self::PLAYER => 'player',
            self::BRONZE => 'bronze',
            self::SILVER => 'silver',
            self::GOLD => 'gold',
            self::PLATINUM => 'platinum',
        };
    }
}

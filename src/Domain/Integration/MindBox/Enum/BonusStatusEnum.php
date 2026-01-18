<?php

namespace Integra\Domain\Integration\MindBox\Enum;

use Integra\Domain\Enum\EnumInterface;

enum BonusStatusEnum:string implements EnumInterface
{
    case NEW = '0';
    case NEW_ALT = '1';
    case ACTIVE = '2';
    case FINISHED = '3';
    case CANCELLED = '4';
    case EXPIRED = '5';

    public function value(): string
    {
        return $this->value;
    }

    public function bonusName():string
    {
        return match ($this) {
            self::NEW, self::NEW_ALT => 'Новый',
            self::ACTIVE => 'Активный',
            self::FINISHED => 'Законченый',
            self::CANCELLED => 'Отмененный',
            self::EXPIRED => 'Истекший',
        };
    }
}

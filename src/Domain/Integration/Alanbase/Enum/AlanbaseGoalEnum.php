<?php
declare(strict_types=1);

namespace Integra\Domain\Integration\MindBox\Enum;

use Integra\Domain\Enum\EnumInterface;

/**
 * Список целей Alanbase.
 */
enum AlanbaseGoalEnum: string implements EnumInterface
{
    /**
     * Регистрация пользователя.
     */
    case REGISTRATION = 'registration';
    case FIRST_TIME_DEPOSIT = 'ftd';
    case DEPOSIT = 'deposit';
    case BONUS = 'bonus';
    case BET = 'betin';
    case BET_WIN = 'betout';
    case DEPOSIT_FEE = 'priem';
    case BONUS_FEE = 'bonusfee';
    case CASHOUT_FEE = 'vivod';
    case RECURRING_DEPOSIT = 'rd';
    case VERIFICATION = 'verification';

    public function value(): string
    {
        return $this->value;
    }
}

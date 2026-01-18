<?php
declare(strict_types=1);

namespace Integra\Domain\Integration\MindBox\Enum;

use Integra\Domain\Enum\EnumInterface;

/**
 * Список операций Alanbase.
 */
enum AlanbaseOperationTypeEnum: string implements EnumInterface
{
    /**
     * Цели
     */
    case GOAL = 'goal';
    /**
     * Цели
     */
    case EVENT = 'event';

    /**
     * Депозит
     */
    case DEPOSIT = 'deposit';

    /**
     * Бонус
     */
    case BONUS = 'bonus';

    /**
     * Ставка
     */
    case BET = 'betin';

    /**
     * Выигрышная ставка
     */
    case BET_WIN = 'betout';

    case DEPOSIT_FEE = 'priem';

    case BONUS_FEE = 'bonusfee';

    case CASHOUT_FEE = 'vivod';


    public function value(): string
    {
        return $this->value;
    }
}

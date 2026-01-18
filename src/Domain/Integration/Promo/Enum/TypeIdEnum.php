<?php
declare(strict_types=1);

namespace Integra\Domain\Integration\Promo\Enum;

use Integra\Domain\Enum\EnumInterface;

/**
 * Идентификаторы типов события? для Promo.
 */
enum TypeIdEnum: string implements EnumInterface
{
    case REGISTRATION = '1';
    case APPROVE = '2';
    case MAIL = '3';
    case TURNOVER = '4';
    case BET = '5';
    case STATUS = '6';
    case VISIT = '7';
    case DEPOSIT = '8';
    case POPOLNENIE = '9';

    public function value(): string
    {
        return $this->value;
    }

    public function label(): string
    {
        return match ($this) {
            self::REGISTRATION => 'Регистрация',
            self::APPROVE => 'Верификация',
            self::MAIL => 'Потверждение почты',
            self::TURNOVER => 'Оборот (результат ставки?)',
            self::BET => 'Ставка 10000+ factor 1.3+',
            self::STATUS => 'Обновление статуса',
            self::VISIT => 'Визит',
            self::DEPOSIT => 'Депозит',
            self::POPOLNENIE => 'Пополнение (Депозит)',
        };
    }
}

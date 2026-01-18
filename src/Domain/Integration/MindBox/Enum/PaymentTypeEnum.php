<?php

namespace Integra\Domain\Integration\MindBox\Enum;

use Integra\Domain\Enum\EnumInterface;

enum PaymentTypeEnum:string implements EnumInterface
{
    case IN = 'IN';
    case OUT = 'OUT';
    case DEPOSIT = 'DEPOSIT';

    public function value(): string
    {
        return $this->value;
    }

    public function operationType(): string
    {
        return match ($this) {
            self::IN => 'Refill',
            self::OUT => 'Withdrawal',
            default => 'Deposit'
        };
    }
}

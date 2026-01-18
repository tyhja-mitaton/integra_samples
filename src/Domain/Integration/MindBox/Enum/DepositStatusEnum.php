<?php

namespace Integra\Domain\Integration\MindBox\Enum;

use Integra\Domain\Enum\EnumInterface;

enum DepositStatusEnum: string implements EnumInterface
{
    case CREATED = '1';
    case SUCCESS = '2';
    case CANCELED = '3';


    public function value(): string
    {
        return $this->value;
    }

    public function mindboxName(): string
    {
        return match ($this) {
            self::CREATED => 'Created',
            self::SUCCESS => 'Success',
            self::CANCELED => 'Canceled',
        };
    }
}

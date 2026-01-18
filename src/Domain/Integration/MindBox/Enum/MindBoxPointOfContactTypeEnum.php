<?php
declare(strict_types=1);

namespace Integra\Domain\Integration\MindBox\Enum;

use Integra\Domain\Enum\EnumInterface;

/**
 * Список каналов уведомления пользователей в MindBox.
 */
enum MindBoxPointOfContactTypeEnum: string implements EnumInterface
{
    case SMS = 'SMS';
    case EMAIL = 'Email';
    case MOBILE_PUSH = 'Mobilepush';

    public function value(): string
    {
        return $this->value;
    }
}

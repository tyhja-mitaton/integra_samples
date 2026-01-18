<?php
declare(strict_types=1);

namespace Integra\Domain\Integration\MindBox\Enum;

use Integra\Domain\Enum\EnumInterface;

/**
 * Список событий Alanbase.
 */
enum AlanbaseEventEnum: string implements EnumInterface
{
    /**
     * Депозит пользователя.
     */
    case DEPOSIT = 'deposit';

    public function value(): string
    {
        return $this->value;
    }
}

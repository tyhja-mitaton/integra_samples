<?php
declare(strict_types=1);

namespace Integra\Domain\Integration\MindBox\Enum;

use Integra\Domain\Enum\EnumInterface;

/**
 * Список статусов Alanbase.
 */
enum AlanbaseStatusEnum: string implements EnumInterface
{
    case CONFIRMED = 'confirmed';

    public function value(): string
    {
        return $this->value;
    }
}

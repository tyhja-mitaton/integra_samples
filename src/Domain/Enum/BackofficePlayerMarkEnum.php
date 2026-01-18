<?php
declare(strict_types=1);

namespace Integra\Domain\Enum;

/**
 * Метки пользователя из сервиса Backoffice
 */
enum BackofficePlayerMarkEnum: string implements EnumInterface
{
    /**
     * "Проблемный" пользователь
     */
    case HAS_PROBLEM = '1';

    /**
     * Пользователю запрещено участие в акциях
     */
    case NO_PROMOTIONS = '16';

    public function value(): string
    {
        return $this->value;
    }
}

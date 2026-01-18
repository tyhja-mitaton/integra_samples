<?php
declare(strict_types=1);

namespace Integra\Domain\Integration\MindBox\Enum;

use Integra\Domain\Enum\EnumInterface;

/**
 * Список типов операций MindBox.
 */
enum MindBoxOperationTypeEnum: string implements EnumInterface
{
    /**
     * По умолчанию
     */
    case SYNC = 'sync';
    /**
     * Используется в операциях массового импорта
     */
    case BULK = 'bulk';

    public function value(): string
    {
        return $this->value;
    }
}

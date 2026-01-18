<?php
declare(strict_types=1);

namespace Integra\Domain\Integration\Adjust\DTO;

use Integra\Domain\Integration\Common\AbstractDTO;

/**
 * DTO с данными пользователя Alanbase для Adjust.
 */
final class AlanbaseUserDataDTO extends AbstractDTO
{
    public function __construct(
        public readonly int $partnerId,
        public readonly int $offerId,
        public readonly int $userId,
    )
    {
    }

    protected function fields(): array
    {
        return [
            'partnerId',
            'offerId',
            'userId'
        ];
    }
}
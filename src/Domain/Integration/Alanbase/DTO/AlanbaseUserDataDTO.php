<?php
declare(strict_types=1);

namespace Integra\Domain\Integration\Alanbase\DTO;

use Integra\Domain\Integration\Common\AbstractDTO;

/**
 * DTO с данными пользователя для Alanbase.
 */
final class AlanbaseUserDataDTO extends AbstractDTO
{
    public function __construct(
        public readonly string $clickId,
        public readonly int    $partnerId,
        public readonly int    $offerId,
        public readonly int    $userId,
    )
    {
    }

    protected function fields(): array
    {
        return [
            'clickId',
            'partnerId',
            'offerId',
            'userId'
        ];
    }
}
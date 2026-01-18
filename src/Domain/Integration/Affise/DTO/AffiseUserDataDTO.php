<?php
declare(strict_types=1);

namespace Integra\Domain\Integration\Affise\DTO;

use Integra\Domain\Integration\Common\AbstractDTO;

/**
 * DTO с данными пользователя для Affise.
 */
final class AffiseUserDataDTO extends AbstractDTO
{
    public function __construct(
        public readonly string $affiseDeviceId,
        public readonly int    $userId,
    )
    {
    }

    protected function fields(): array
    {
        return [
            'affiseDeviceId',
            'userId',
        ];
    }
}

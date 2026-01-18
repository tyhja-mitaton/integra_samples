<?php
declare(strict_types=1);

namespace Integra\Domain\Integration\Affise\Operation\Register\Data;

use Integra\Domain\Integration\Affise\DTO\AffiseUserDataDTO;

final class RegisterDataBuilder
{
    /**
     * @param string $affiseDeviceId
     * @param int $userId
     * @return AffiseUserDataDTO
     */
    public function build(string $affiseDeviceId, int $userId): AffiseUserDataDTO
    {
        return new AffiseUserDataDTO(
            affiseDeviceId: $affiseDeviceId,
            userId: $userId,
        );
    }
}

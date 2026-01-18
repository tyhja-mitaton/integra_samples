<?php
declare(strict_types=1);

namespace Integra\Domain\Integration\Affise\Operation\Register\DTO;

use Integra\Domain\Integration\Common\AbstractDTO;

/**
 * DTO для запроса регистрации в Affise.
 */
final class RegisterDTO extends AbstractDTO
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
            'affiseDeviceId' => 'affise_device_id',
            'userId' => 'ubet_user_id',
        ];
    }
}
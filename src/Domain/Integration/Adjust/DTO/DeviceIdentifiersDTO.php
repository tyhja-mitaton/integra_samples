<?php
declare(strict_types=1);

namespace Integra\Domain\Integration\Adjust\DTO;

use Integra\Domain\Integration\Common\AbstractDTO;

/**
 * DTO для идентификаторов устройства Adjust S2S.
 */
final class DeviceIdentifiersDTO extends AbstractDTO
{
    public function __construct(
        public readonly ?string $adjustAdid,
        public readonly ?string $gpsAdid,
        public readonly ?string $idfa,
        public readonly ?string $idfv,
    ) {}

    protected function fields(): array
    {
        return [
            'adjustAdid' => 'adid',
            'gpsAdid' => 'gps_adid',
            'idfa',
            'idfv',
        ];
    }
}

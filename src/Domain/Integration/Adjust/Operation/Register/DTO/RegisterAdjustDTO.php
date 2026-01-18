<?php
declare(strict_types=1);

namespace Integra\Domain\Integration\Adjust\Operation\Register\DTO;

use Integra\Domain\Integration\Common\AbstractDTO;
use Integra\Domain\Integration\Adjust\DTO\DeviceIdentifiersDTO;

final class RegisterAdjustDTO extends AbstractDTO
{
    public function __construct(
        public readonly CallbackParamsDTO    $callbackParams,
        public readonly DeviceIdentifiersDTO $identifiers,
    )
    {
    }

    protected function fields(): array
    {
        return [
            'callbackParams' => 'callback_params',
            'identifiers',
        ];
    }
}
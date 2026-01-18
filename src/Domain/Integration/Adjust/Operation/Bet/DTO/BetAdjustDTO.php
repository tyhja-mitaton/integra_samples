<?php

namespace Integra\Domain\Integration\Adjust\Operation\Bet\DTO;

use Integra\Domain\Integration\Common\AbstractDTO;

class BetAdjustDTO extends AbstractDTO
{
    public function __construct(
        public readonly CallbackParamsDTO    $callbackParams,
    )
    {
    }

    /**
     * @inheritDoc
     */
    protected function fields(): array
    {
        return [
            'callbackParams' => 'callback_params',
        ];
    }
}
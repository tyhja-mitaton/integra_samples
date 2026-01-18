<?php

namespace Integra\Domain\Integration\Adjust\Operation\FirstTimeDeposit\DTO;

use Integra\Domain\Integration\Common\AbstractDTO;

class FirstTimeDepositAdjustDTO extends AbstractDTO
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
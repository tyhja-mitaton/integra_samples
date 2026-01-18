<?php

namespace Integra\Domain\Integration\AppsFlyer\Operation\Deposit\DTO;

use Integra\Domain\Integration\Common\AbstractDTO;

class DepositAppsFlyerDTO extends AbstractDTO
{
    public function __construct(
        public readonly string $appsflyerId,
        public readonly string $eventName,
        public readonly EventValueDTO $eventValue,
    )
    {
    }

    /**
     * @inheritDoc
     */
    protected function fields(): array
    {
        return [
            'appsflyerId' =>'appsflyer_id',
            'eventName',
            'eventValue'
        ];
    }
}
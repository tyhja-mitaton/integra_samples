<?php

namespace Integra\Domain\Integration\MindBox\Operation\CreateDeposit\DTO;

use Integra\Domain\Integration\Common\AbstractDTO;

class CustomFieldsDTO extends AbstractDTO
{
    public function __construct(
        public readonly ?string $eventPlatform,
        public readonly ?string $eventDateAndTime,
        public string $operationType,
        public readonly string $orderType,
        public readonly float $depositAmount,
        public readonly ?string $replenishmentMethod
    )
    {
    }

    /**
     * @inheritDoc
     */
    protected function fields(): array
    {
        return [
            'eventPlatform',
            'eventDateAndTime',
            'operationType',
            'orderType',
            'depositAmount',
            'replenishmentMethod',
        ];
    }
}
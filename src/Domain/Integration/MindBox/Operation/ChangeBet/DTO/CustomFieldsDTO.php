<?php

namespace Integra\Domain\Integration\MindBox\Operation\ChangeBet\DTO;

use Integra\Domain\Integration\Common\AbstractDTO;

class CustomFieldsDTO extends AbstractDTO
{
    public function __construct(
        public float $betAmount,
        public int|string|null $betOnBonus,
        public ?string $betType,
        public string $eventDateAndTime,
        public string $finalBetOdds,
        public string $orderType
    )
    {
    }

    /**
     * @inheritDoc
     */
    protected function fields(): array
    {
        return [
            'betAmount',
            'betOnBonus',
            'betType',
            'eventDateAndTime',
            'finalBetOdds',
            'orderType',
        ];
    }
}
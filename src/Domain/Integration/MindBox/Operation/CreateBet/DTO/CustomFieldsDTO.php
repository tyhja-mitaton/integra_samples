<?php

namespace Integra\Domain\Integration\MindBox\Operation\CreateBet\DTO;

use Integra\Domain\Integration\Common\AbstractDTO;

class CustomFieldsDTO extends AbstractDTO
{
    public function __construct(
        public readonly ?float $betAmount,
        public readonly int|string|null $betOnBonus,
        public readonly ?string $betType,
        public readonly string $eventDateAndTime,
        public readonly ?string $eventPlatform,
        public readonly string $finalBetOdds,
        public readonly string $orderType,
        public readonly ?float $prizeAmount,
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
            'eventPlatform',
            'finalBetOdds',
            'orderType',
            'prizeAmount' => 'PrizeAmount'
        ];
    }
}
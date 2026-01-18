<?php

namespace Integra\Domain\Integration\MindBox\Operation\CreateBonus\DTO;

use Integra\Domain\Integration\Common\AbstractDTO;

class CustomFieldsDTO extends AbstractDTO
{
    public function __construct(
        public readonly ?string $bonusAccrualDateAndTime,
        public readonly ?float $bonuseAmount,
        public readonly ?string $dateAndTimeOfBonusExpiration,
        public readonly string $orderType,
        public readonly ?int $bonusId,
        public readonly string $bonusType,
        public readonly ?string $winbackDateAndTime,
        public readonly ?string $bonusActivationDateAndTime,
        public readonly ?float $minimumoddsforbet,
        public readonly string $typeOfBonusBet,
        public readonly int $daysForActivation,
        public readonly int $daysForWinback,
        public readonly string $bonusName,
        public readonly int $wageringCoefficient,
        public readonly ?string $winbackAmount
    )
    {
    }

    /**
     * @inheritDoc
     */
    protected function fields(): array
    {
        return [
            'bonusAccrualDateAndTime',
            'bonuseAmount',
            'dateAndTimeOfBonusExpiration',
            'orderType',
            'bonusId',
            'bonusType',
            'winbackDateAndTime',
            'bonusActivationDateAndTime',
            'minimumoddsforbet',
            'typeOfBonusBet',
            'daysForActivation',
            'daysForWinback',
            'bonusName',
            'wageringCoefficient',
            'winbackAmount',
        ];
    }
}
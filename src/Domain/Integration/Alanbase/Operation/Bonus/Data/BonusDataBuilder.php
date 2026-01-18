<?php

namespace Integra\Domain\Integration\Alanbase\Operation\Bonus\Data;

use Integra\Domain\Integration\Alanbase\Operation\Bonus\DTO\BonusDTO;
use Integra\Domain\Integration\MindBox\Enum\AlanbaseGoalEnum;
use Integra\Models\Ubet\UserTotoBonus;
use DomainException;

final class BonusDataBuilder
{
    const CURRENCY = 'KZT';

    public function build($bonusId, $clickId): BonusDTO
    {
        $userTotoBonus = UserTotoBonus::findOne(['bonus_id' => $bonusId]);

        if(empty($userTotoBonus)){
            throw new DomainException("Bonus not found: {$bonusId}");
        }

        return new BonusDTO(
            clickId: $clickId,
            event: AlanbaseGoalEnum::BONUS->value,
            value: $userTotoBonus->amount,
            currency: self::CURRENCY,
            datetime: strtotime($userTotoBonus->create_dttm)
        );
    }
}
<?php

namespace Integra\Domain\Integration\Alanbase\Operation\BetNew\Data;

use Integra\Domain\Integration\Alanbase\Operation\BetNew\DTO\BetNewDTO;
use Integra\Domain\Integration\MindBox\Enum\AlanbaseGoalEnum;
use Integra\Infrastructure\Datetime\Asia;
use Integra\Infrastructure\Datetime\UTC;
use Integra\Models\Ubet\SportBet;
use DateTime;

final class BetWinDataBuilder
{
    const STATUS_CONFIRMED = 'confirmed';

    public function build($betId, $clickId): BetNewDTO
    {
        $bet = SportBet::findOne(['bet_id' => $betId]);

        $registrationDateTime = new DateTime($bet->bet_dttm, new Asia());
        $registrationDateTime->setTimezone(new UTC());

        return new BetNewDTO(
            clickId: $clickId,
            goal: AlanbaseGoalEnum::BET_WIN->value,
            status: self::STATUS_CONFIRMED,
            value: $bet->bet_win_real,
            currency: $bet->currency_id,
            datetime: $registrationDateTime->getTimestamp()
        );
    }
}
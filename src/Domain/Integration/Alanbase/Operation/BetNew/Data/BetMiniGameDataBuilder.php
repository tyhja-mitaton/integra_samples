<?php

namespace Integra\Domain\Integration\Alanbase\Operation\BetNew\Data;

use Integra\Domain\Integration\Alanbase\Operation\BetNew\DTO\BetNewDTO;
use Integra\Domain\Integration\MindBox\Enum\AlanbaseGoalEnum;
use Integra\Infrastructure\Datetime\UTC;
use Integra\Models\Ubet\GameBet;
use DateTime;

final class BetMiniGameDataBuilder
{
    const STATUS_CONFIRMED = 'confirmed';
    const CURRENCY = 'KZT';

    public function build($gameId, $clickId): BetNewDTO
    {
        $bet = GameBet::findOne(['bet_id' => $gameId]);

        $betDateTime = new DateTime($bet->dttm_at, new UTC());

        return new BetNewDTO(
            clickId: $clickId,
            goal: AlanbaseGoalEnum::BET->value,
            status: self::STATUS_CONFIRMED,
            value: abs((int)$bet->amount),
            currency: self::CURRENCY,
            datetime: $betDateTime->getTimestamp()
        );
    }
}
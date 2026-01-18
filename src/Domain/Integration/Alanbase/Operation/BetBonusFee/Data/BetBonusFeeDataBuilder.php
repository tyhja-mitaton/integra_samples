<?php

namespace Integra\Domain\Integration\Alanbase\Operation\BonusFee\Data;

use Integra\Domain\Integration\Alanbase\Operation\BetBonusFee\DTO\BetBonusFeeDTO;
use Integra\Domain\Integration\MindBox\Enum\AlanbaseGoalEnum;
use Integra\Infrastructure\Datetime\Asia;
use Integra\Infrastructure\Datetime\UTC;
use Integra\Models\Ubet\SportBet;
use Integra\Models\Ubet\TransactionBonus;
use DateTime;

final class BetBonusFeeDataBuilder
{
    const STATUS = 'confirmed';

    public function build($betId, $clickId): BetBonusFeeDTO
    {
        $bet = SportBet::findOne(['bet_id' => $betId]);

        $transaction = TransactionBonus::findOne(['transaction_id' => $bet->transaction_id]);

        $betBonusDateTime = new DateTime($bet->bet_dttm, new Asia());
        $betBonusDateTime->setTimezone(new UTC());

        return new BetBonusFeeDTO(
            clickId: $clickId,
            goal: AlanbaseGoalEnum::BONUS_FEE->value,
            status: self::STATUS,
            value: $transaction->amount_real,
            currency: $bet->currency_id,
            userId: $transaction->user_id,
            datetime: $betBonusDateTime->getTimestamp()
        );
    }
}
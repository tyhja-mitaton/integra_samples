<?php

namespace Integra\Domain\Integration\Adjust\Operation\Bet\Data;

use Integra\Domain\Integration\Adjust\Operation\Bet\DTO\BetAdjustDTO;
use Integra\Domain\Integration\Adjust\Operation\Bet\DTO\CallbackParamsDTO;
use Integra\Domain\Integration\MindBox\Enum\AlanbaseGoalEnum;
use Integra\Infrastructure\Datetime\Asia;
use Integra\Infrastructure\Datetime\UTC;
use Integra\Models\Ubet\SportBet;
use DateTime;

final class CreditBetAdjustDataBuilder
{
    private const STATUS_CONFIRMED = 'confirmed';

    public function build(int $userId, int $orderNumber, string  $partnerId = '', ?string $offerId = '',): BetAdjustDTO
    {
        $bet = SportBet::findOne(['order_number' => $orderNumber, 'tr_name' => 'CreditBet']);

        $betDateTime = new DateTime($bet->bet_dttm, new Asia());
        $betDateTime->setTimezone(new UTC());

        $callbackDto = new CallbackParamsDTO(
            userId: (string)$userId,
            status: self::STATUS_CONFIRMED,
            goal: AlanbaseGoalEnum::BET->value,
            amount: (string)$bet->bet_sum,
            currency: $bet->currency_id,
            datetime: (string)$betDateTime->getTimestamp(),
            partnerId: $partnerId ?? null,
            offerId: $offerId,
        );

        return new BetAdjustDTO(
            callbackParams: $callbackDto
        );
    }
}
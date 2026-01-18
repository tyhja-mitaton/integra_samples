<?php

namespace Integra\Domain\Integration\Promo\Operation\Bet\Data;

use Integra\Domain\Integration\Promo\Enum\TypeIdEnum;
use Integra\Domain\Integration\Promo\Operation\Bet\DTO\BetPromoDTO;
use Integra\Models\Ubet\SportBet;
use yii\helpers\StringHelper;
use DomainException;
use Yii;

final class BetResultBetDataBuilder
{
    public function build(int $betId):BetPromoDTO
    {
        $bet = SportBet::findOne($betId);

        if (empty($bet)) {
            $message = sprintf(
                '[%s] Bet not found:: %s',
                StringHelper::basename(self::class),
                $betId
            );
            Yii::error($message, __METHOD__);
            throw new DomainException($message);
        }

        return new BetPromoDTO(
            userId: $bet->user_id,
            orderNumber: $bet->order_number,
            typeId: (int)TypeIdEnum::BET->value()
        );
    }
}
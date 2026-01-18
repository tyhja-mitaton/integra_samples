<?php

namespace Integra\Domain\Integration\MindBox\Operation\ChangeBetStatus\Data;

use Integra\Domain\Integration\MindBox\Enum\BetStateEnum;
use Integra\Domain\Integration\MindBox\Operation\ChangeBetStatus\DTO\ChangeBetStatusDTO;
use Integra\Domain\Integration\MindBox\Operation\ChangeBetStatus\DTO\CustomFieldsDTO;
use Integra\Domain\Integration\MindBox\Operation\ChangeBetStatus\DTO\IdsDTO;
use Integra\Domain\Integration\MindBox\Operation\ChangeBetStatus\DTO\OrderDTO;
use Integra\Infrastructure\Datetime\Asia;
use Integra\Infrastructure\Datetime\UTC;
use Integra\Models\Ubet\SportBet;
use yii\helpers\StringHelper;
use Yii;
use DomainException;
use DateTime;

final class ChangeBetStatusDataBuilder
{
    public function build(int $betId): ChangeBetStatusDTO
    {
        $bet = SportBet::findOne(['bet_id' => $betId]);

        if (!$bet) {
            $message = sprintf(
                '[%s] Bet not found:: %s',
                StringHelper::basename(self::class),
                $betId
            );
            Yii::error($message, __METHOD__);
            throw new DomainException($message);
        }

        $betDateTime = new DateTime($bet->bet_dttm, new Asia());
        $betDateTime->setTimezone(new UTC());
        $betDateTimeUTC = $betDateTime->modify('+1 second')->format('Y-m-d H:i:s');

        return new ChangeBetStatusDTO(
            orderLinesStatus: BetStateEnum::from($bet->bet_state)->stateName(),
            order: new OrderDTO(
                ids: new IdsDTO(
                    betId: $bet->order_number
                ),
                customFields: new CustomFieldsDTO(
                    prizeAmount: $bet->bet_win
                )
            ),
            executionDateTimeUtc: $betDateTimeUTC
        );
    }
}
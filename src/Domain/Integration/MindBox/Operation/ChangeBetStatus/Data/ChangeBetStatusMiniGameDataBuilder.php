<?php

namespace Integra\Domain\Integration\MindBox\Operation\ChangeBetStatus\Data;

use Integra\Domain\Integration\MindBox\Enum\BetStateGameEnum;
use Integra\Domain\Integration\MindBox\Operation\ChangeBetStatus\DTO\ChangeBetStatusDTO;
use Integra\Domain\Integration\MindBox\Operation\ChangeBetStatus\DTO\CustomFieldsDTO;
use Integra\Domain\Integration\MindBox\Operation\ChangeBetStatus\DTO\IdsDTO;
use Integra\Domain\Integration\MindBox\Operation\ChangeBetStatus\DTO\OrderDTO;
use Integra\Infrastructure\Datetime\UTC;
use Integra\Models\Ubet\GameBet;
use Integra\Models\Ubet\GameType;
use DateTime;

final class ChangeBetStatusMiniGameDataBuilder
{
    public function build(int $gameId): ChangeBetStatusDTO
    {
        $bet = GameBet::findOne(['bet_id' => $gameId]);
        $betType = GameType::findOne($bet->type_id);

        $betDateTime = new DateTime($bet->dttm_at, new UTC());
        $betDateTimeUTC = $betDateTime->modify('+1 second')->format('Y-m-d H:i:s');

        return new ChangeBetStatusDTO(
            orderLinesStatus: BetStateGameEnum::from((int)$betType->id)->stateName(),
            order: new OrderDTO(
                ids: new IdsDTO(
                    betId: $bet->provider_bet_id
                ),
                customFields: new CustomFieldsDTO(
                    prizeAmount: abs((float)$bet->amount)
                )
            ),
            executionDateTimeUtc: $betDateTimeUTC
        );
    }
}
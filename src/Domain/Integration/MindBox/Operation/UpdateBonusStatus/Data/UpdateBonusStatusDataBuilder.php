<?php

namespace Integra\Domain\Integration\MindBox\Operation\UpdateBonusStatus\Data;

use Integra\Domain\Integration\MindBox\Enum\BonusStatusEnum;
use Integra\Domain\Integration\MindBox\Operation\UpdateBonusStatus\DTO\UpdateBonusStatusDTO;
use Integra\Domain\Integration\MindBox\Operation\UpdateBonusStatus\DTO\OrderDTO;
use Integra\Domain\Integration\MindBox\Operation\UpdateBonusStatus\DTO\IdsDTO;
use Integra\Infrastructure\Datetime\UTC;

final class UpdateBonusStatusDataBuilder
{
    public function build(int $bonusId, int $statusId, ?string $executedDateTimeUtc = null):UpdateBonusStatusDTO
    {
        return new UpdateBonusStatusDTO(
            executionDateTimeUtc: $executedDateTimeUtc ?? (new \DateTime('now', new UTC()))->format('Y-m-d H:i:s'),
            orderLinesStatus: BonusStatusEnum::from($statusId)->bonusName(),
            order: new OrderDTO(
                ids: new IdsDTO(
                    bonuseId: $bonusId
                )
            )
        );
    }
}
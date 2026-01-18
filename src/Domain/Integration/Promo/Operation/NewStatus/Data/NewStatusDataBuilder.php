<?php

namespace Integra\Domain\Integration\Promo\Operation\NewStatus\Data;

use Integra\Domain\Integration\Promo\Enum\TypeIdEnum;
use Integra\Domain\Integration\Promo\Operation\NewStatus\DTO\NewStatusPromoDTO;

final class NewStatusDataBuilder
{
    public function build(int $userId, int $statusId):NewStatusPromoDTO
    {
        return new NewStatusPromoDTO(
            userId: $userId,
            statusId: $statusId,
            typeId: (int)TypeIdEnum::STATUS->value()
        );
    }
}
<?php

namespace Integra\Domain\Integration\Promo\Operation\Verification\Data;

use Integra\Domain\Integration\Promo\Enum\TypeIdEnum;
use Integra\Domain\Integration\Promo\Operation\Verification\DTO\VerificationPromoDTO;

final class VerificationDataBuilder
{
    public function build(int $userId):VerificationPromoDTO
    {
        return new VerificationPromoDTO(
            userId: $userId,
            typeId: (int)TypeIdEnum::APPROVE->value()
        );
    }
}
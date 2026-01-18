<?php

namespace Integra\Domain\Integration\Promo\Operation\Email\Data;

use Integra\Domain\Integration\Promo\Enum\TypeIdEnum;
use Integra\Domain\Integration\Promo\Operation\Email\DTO\EmailPromoDTO;

final class EmailDataBuilder
{
    public function build(int $userId):EmailPromoDTO
    {
        return new EmailPromoDTO(
            userId: $userId,
            typeId: (int)TypeIdEnum::MAIL->value()
        );
    }
}
<?php

namespace Integra\Domain\Integration\Adjust\Operation\Verification\Data;

use Integra\Domain\Integration\Adjust\Operation\Verification\DTO\CallbackParamsDTO;
use Integra\Domain\Integration\Adjust\Operation\Verification\DTO\VerificationAdjustDTO;
use Integra\Domain\Integration\MindBox\Enum\AlanbaseGoalEnum;
use Integra\Models\Ubet\User;

final class VerificationAdjustDataBuilder
{
    private const STATUS_CONFIRMED = 'confirmed';

    public function build(int $userId, string  $partnerId = '', ?string $offerId = '')
    {
        $user = User::findOne($userId);

        return new VerificationAdjustDTO(
            callbackParams: new CallbackParamsDTO(
                userId: (string)$userId,
                status: self::STATUS_CONFIRMED,
                goal: AlanbaseGoalEnum::VERIFICATION->value,
                datetime: (string)strtotime($user->dttm_approve),
                partnerId: $partnerId ?? null,
                offerId: (string)$offerId,
            )
        );
    }
}
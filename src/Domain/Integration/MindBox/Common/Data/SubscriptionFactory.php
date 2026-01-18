<?php
declare(strict_types=1);

namespace Integra\Domain\Integration\MindBox\Common\Data;

use Integra\Domain\Integration\MindBox\DTO\SubscriptionDTO;
use Integra\Domain\Integration\MindBox\Enum\MindBoxPointOfContactTypeEnum;
use Integra\Models\Ubet\User;

/**
 * Factory for creating SubscriptionDTO arrays for MindBox operations.
 */
final class SubscriptionFactory
{
    const BRAND = 'ubet';

    /**
     * Возвращает список SubscriptionDTO для основных каналов уведомлений.
     * @param User $user
     * @return \Integra\Domain\Integration\MindBox\DTO\SubscriptionDTO[]
     */
    public function forUser(User $user): array
    {
        return [
            new SubscriptionDTO(self::BRAND, MindBoxPointOfContactTypeEnum::SMS->value(), $user->is_notice_sms),
            new SubscriptionDTO(self::BRAND, MindBoxPointOfContactTypeEnum::EMAIL->value(), $user->is_notice_email),
            new SubscriptionDTO(self::BRAND, MindBoxPointOfContactTypeEnum::MOBILE_PUSH->value(), $user->is_notice_push),
        ];
    }
}

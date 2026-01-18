<?php
declare(strict_types=1);

namespace Integra\Domain\Integration\Alanbase\Operation\RegisterGoal\Data;

use DateTime;
use Exception;
use DomainException;
use Integra\Models\Ubet\User;
use Integra\Infrastructure\Datetime\UTC;
use Integra\Infrastructure\Datetime\Asia;
use Integra\Domain\Integration\MindBox\Enum\AlanbaseGoalEnum;
use Integra\Domain\Integration\MindBox\Enum\AlanbaseStatusEnum;
use Integra\Domain\Integration\Alanbase\Operation\RegisterGoal\DTO\RegisterGoalDTO;

final class RegisterGoalDataBuilder
{
    /**
     * @param int $userId
     * @param string $clickId
     * @return RegisterGoalDTO
     * @throws Exception
     */
    public function build(int $userId, string $clickId): RegisterGoalDTO
    {
        $user = User::findOne(['user_id' => $userId]);

        if (!$user) {
            throw new DomainException("User not found: {$userId}");
        }

        $registrationDateTime = new DateTime($user->dttm_reg, new Asia());
        //todo тут тоже что и в RegisterAdjustDataBuilder, -6 часов вместо -5
        $registrationDateTime->setTimezone(new UTC());
        $timestamp = $registrationDateTime->getTimestamp();

        return new RegisterGoalDTO(
            clickId: $clickId,
            goal: AlanbaseGoalEnum::REGISTRATION->value,
            status: AlanbaseStatusEnum::CONFIRMED->value,
            userId: $userId,
            datetime: $timestamp,
        );
    }
}
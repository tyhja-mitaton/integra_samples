<?php

declare(strict_types=1);

namespace Integra\Domain\Services;

use DomainException;
use Integra\Models\Ubet\User;
use Integra\Models\Ubet\UsersAffiliate;
use Integra\Domain\Integration\Adjust\DTO\AlanbaseUserDataDTO;

/**
 * Сервис Adjust
 */
class AdjustService
{
    /**
     * Проверяет, существуют ли в Alanbase данные для пользователя adjust.
     * @param int $userId
     * @return bool
     */
    public function isAlanbaseUser(int $userId): bool
    {
        try {
            $this->fetchData($userId);
            return true;
        } catch (DomainException) {
            return false;
        }
    }

    /**
     * Данные Alanbase для пользователя adjust.
     * @param int $userId
     * @return AlanbaseUserDataDTO
     */
    public function getAlanbaseDataByUserId(int $userId): AlanbaseUserDataDTO
    {
        $data = $this->fetchData($userId);

        return new AlanbaseUserDataDTO(
            partnerId: $data['partnerId'],
            offerId: $data['offerId'],
            userId: $data['userId'],
        );
    }

    /**
     * @param int $userId
     * @return array{partnerId:int, offerId:int, userId:int}
     * @throws DomainException
     */
    private function fetchData(int $userId): array
    {
        $user = User::findOne(['user_id' => $userId]);

        if (!$user) {
            throw new DomainException("User not found dy userId: {$userId}");
        }

        $affiliate = UsersAffiliate::findOne(['alanbase_partner_id' => $user->a_partner_id]);

        if (!$affiliate || (int)$affiliate->alanbase_status === 0) {
            throw new DomainException("Alanbase affiliate not active or empty for partner {$user->a_partner_id}");
        }

        return [
            'partnerId' => $user->a_partner_id,
            'offerId' => $user->a_offer_id,
            'userId' => $userId,
        ];
    }
}
<?php

declare(strict_types=1);

namespace Integra\Domain\Services;

use DomainException;
use Integra\Models\Ubet\User;
use Integra\Models\Ubet\UsersAffiliate;
use Integra\Domain\Integration\Alanbase\DTO\AlanbaseUserDataDTO;

/**
 * Сервис Alanbase
 */
class AlanbaseService
{
    /**
     * Проверяет, существуют ли в Alanbase данные для пользователя.
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
     * Данные Alanbase для пользователя.
     * @param int $userId
     * @return AlanbaseUserDataDTO
     */
    public function getAlanbaseDataByUserId(int $userId): AlanbaseUserDataDTO
    {
        $data = $this->fetchData($userId);

        return new AlanbaseUserDataDTO(
            clickId: $data['clickId'],
            partnerId: $data['partnerId'],
            offerId: $data['offerId'],
            userId: $data['userId'],
        );
    }

    /**
     * @return array{clickId:string, partnerId:int, offerId:int, userId:int}
     * @throws DomainException
     */
    private function fetchData(int $userId): array
    {
        $user = User::findOne(['user_id' => $userId]);
        if (!$user || empty($user->a_click_id)) {
            throw new DomainException("Alanbase click_id not found for user {$userId}");
        }

        $affiliate = UsersAffiliate::findOne(['alanbase_partner_id' => $user->a_partner_id]);
        if (!$affiliate || (int)$affiliate->alanbase_status === 0) {
            throw new DomainException("Alanbase affiliate not active for partner {$user->a_partner_id}");
        }

        return [
            'clickId' => $user->a_click_id,
            'partnerId' => $user->a_partner_id,
            'offerId' => $user->a_offer_id,
            'userId' => $userId,
        ];
    }
}
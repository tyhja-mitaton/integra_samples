<?php
declare(strict_types=1);

namespace Integra\Domain\Services;

use DomainException;
use yii\db\Exception;
use Integra\Models\Ubet\User;
use Integra\Infrastructure\Generic\Result;
use Integra\Models\Ubet\UsersAffiseReceive;
use Integra\Domain\Integration\Affise\DTO\AffiseUserDataDTO;

/**
 * Сервис Affise.
 */
final class AffiseService
{
    /**
     * Проверяет, существуют ли в Affise данные для пользователя.
     * @param int $userId
     * @return bool
     */
    public function isAffiseUser(int $userId): bool
    {
        $user = User::findOne(['user_id' => $userId]);
        return $user !== null && !empty($user->affise_device_id);
    }

    /**
     *  Данные Alanbase для пользователя.
     * @param int $userId
     * @return AffiseUserDataDTO
     */
    public function getAffiseDataByUserId(int $userId): AffiseUserDataDTO
    {
        $user = User::findOne(['user_id' => $userId]);
        if (!$user || empty($user->affise_device_id)) {
            throw new DomainException("Affise device ID not found for user {$userId}");
        }

        return new AffiseUserDataDTO(
            affiseDeviceId: $user->affise_device_id,
            userId: $userId,
        );
    }

    /**
     * Записывает результат отправки данных в таблицу.
     * @param Result $result
     * @param int $userId
     * @param array $payload
     * @throws Exception
     */
    public function record(Result $result, int $userId, array $payload): void
    {
        $model = new UsersAffiseReceive();
        $model->user_id = $userId;
        $model->data = json_encode($payload, JSON_UNESCAPED_UNICODE);
        $model->is_sent = $result->isSuccessful() ? 1 : 0;
        $model->message = $result->isSuccessful() ? null : json_encode($result->error(), JSON_UNESCAPED_UNICODE);
        $model->created_at = date('Y-m-d H:i:s');
        $model->save(false);
    }
}
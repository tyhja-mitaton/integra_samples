<?php

declare(strict_types=1);

namespace Integra\Domain\Services\User;

use Integra\Domain\Enum\CashbackLevelEnum;
use Integra\Models\Ubet\UsersStatusCurrent;
use Integra\Models\Ubet\UsersStatusHistory;

/**
 * Сервис для определения cashback-статуса пользователя на основе текущего и исторического статусов.
 */
class CashbackService
{
    /**
     * Возвращает статус кэшбэка для пользователя по последним status_current/history
     * @param int $userId
     * @return string
     */
    public function getCashbackStatus(int $userId): string
    {
        $current = UsersStatusCurrent::find()->where(['user_id' => $userId])->orderBy(['id' => SORT_DESC])->one();
        $history = UsersStatusHistory::find()->where(['user_id' => $userId])->orderBy(['id' => SORT_DESC])->one();

        $key = max((int)($current?->status_id ?? 1), (int)($history?->status_id ?? 1));
        $level = CashbackLevelEnum::tryFrom((string)$key) ?? CashbackLevelEnum::PLAYER;

        return $level->label();
    }
}
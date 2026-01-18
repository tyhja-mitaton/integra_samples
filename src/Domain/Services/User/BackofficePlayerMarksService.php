<?php

declare(strict_types=1);

namespace Integra\Domain\Services\User;

use Integra\Domain\Enum\BackofficePlayerMarkEnum;
use Integra\Models\Backoffice\PlayersMarks;

/**
 * Сервис пользовательских "меток" в админке backoffice
 */
class BackofficePlayerMarksService
{
    /**
     * Ограничение участия в акциях
     * @param int $userId
     * @return bool
     */
    public function isSharesRestriction(int $userId): bool
    {
        return PlayersMarks::find()
            ->where(['player_id' => $userId, 'mark_id' => BackofficePlayerMarkEnum::NO_PROMOTIONS->value()])
            ->exists();
    }

    /**
     * Проблемный пользователь
     * @param int $userId
     * @return bool
     */
    public function isProblem(int $userId): bool
    {
        return PlayersMarks::find()
            ->where(['player_id' => $userId, 'mark_id' => BackofficePlayerMarkEnum::HAS_PROBLEM->value()])
            ->exists();
    }
}
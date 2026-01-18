<?php

declare(strict_types=1);

namespace Integra\Domain\Services;

use Integra\Infrastructure\Queue\Job\Integrations\MindBox\MindBoxEditUserJob;
use Integra\Infrastructure\Queue\Job\Integrations\MindBox\MindBoxRegistrationJob;
use Yii;
use Integra\Models\Ubet\User;
use yii\helpers\StringHelper;
use Integra\Domain\Integration\TempLocalDebug;

/**
 * Сервис MindBox
 */
class MindBoxService
{
    /**
     * Помечает пользователя как успешно отправленного в MindBox.
     * Наличие данного статуса означает что данный пользователь существует в системе MindBox.
     */
    public function markUserAsExistedInMindBoxSystem(int $userId): void
    {
        /** @var User $user */
        $user = User::findOne(['user_id' => $userId]);
        if (!$user) {
            $message = sprintf(
                '[%s] user %d not found',
                StringHelper::basename(static::class),
                $userId,
            );
            Yii::warning($message, __METHOD__);
            TempLocalDebug::echo($message);
            return;
        }

        $user->to_mindbox = 1;

        if (!$user->save()) {
            $message = sprintf(
                '[%s] Failed to mark user %d as to_mindbox: %s',
                StringHelper::basename(static::class),
                $userId,
                json_encode($user->getErrors(), JSON_UNESCAPED_UNICODE)
            );
            Yii::error($message, __METHOD__);
            TempLocalDebug::echo($message);
        }
//        TempLocalDebug::echo('✅ user saved success');
    }

    public function reactivateUserIfNotExistMindbox(int $userId, bool $need_active = false): bool
    {
        $user = User::findOne(['user_id' => $userId]);

        if (!$user) {
            $message = sprintf(
                '[%s] user %d not found',
                StringHelper::basename(static::class),
                $userId,
            );
            Yii::warning($message, __METHOD__);
            TempLocalDebug::echo($message);
            return false;
        }

        if (!$user->to_mindbox && $need_active) {
            $job = new MindBoxRegistrationJob();
            $job->userId = $userId;
            $job->push();

            $job = new MindBoxEditUserJob();
            $job->userId = $userId;
            $job->push();

            return true;
        }

        return (bool)$user->to_mindbox;
    }
}
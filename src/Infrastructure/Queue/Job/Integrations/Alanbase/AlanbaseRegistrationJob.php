<?php
declare(strict_types=1);

namespace Integra\Infrastructure\Queue\Job\Integrations\Alanbase;

use Yii;
use yii\queue\Queue;
use yii\helpers\StringHelper;
use Integra\Models\Ubet\User;
use Integra\Infrastructure\Http\Transport;
use Integra\Infrastructure\Environment\Env;
use Integra\Domain\Integration\Common\OperationHandlerInterface;
use Integra\Infrastructure\Queue\Exception\LimitedRetryJobException;
use Integra\Infrastructure\Queue\Job\Integrations\AbstractIntegrationJob;
use Integra\Infrastructure\Queue\Job\Integrations\IntegrationJobInterface;
use Integra\Domain\Integration\Alanbase\Operation\RegisterLead\Handler\RegisterGoalHandler;

class AlanbaseRegistrationJob extends AbstractIntegrationJob implements IntegrationJobInterface
{
    public string $clickId;
    public int    $userId;
    public ?int $timeoutSeconds = null;

    public function getQueueComponent(): Queue
    {
        return Yii::$app->queue_triggers_alanbase;
    }

    public function getPushDelaySeconds(): int
    {
        return (int)(new Env('UB_FIRST_TRY_DELAY'))->value();
    }

    protected function getRawParams(): array
    {
        //todo во всех джобах переделать, тут проверка на существование пользователя, чтоб limited retry был
        // а внутрь уже User передавать чтоб два раза не чекать + запросов меньше будет
        $user = User::findOne(['user_id' => $this->userId]);
        if (empty($user)) {
            $message = sprintf(
                '[%s] User not found:: %s',
                StringHelper::basename(static::class),
                $this->userId
            );
            Yii::error($message, __METHOD__);
            throw new LimitedRetryJobException($message);
        }

        return [
            'clickId' => $this->clickId,
            'userId'  => $user->user_id,
            'timeoutSeconds' => $this->timeoutSeconds,
        ];
    }

    /**
     * @param Transport $transport
     * @return OperationHandlerInterface
     */
    protected function createHandler(Transport $transport): OperationHandlerInterface
    {
        return new RegisterGoalHandler(
            $transport
        );
    }
}

<?php

namespace Integra\Infrastructure\Queue\Job\Integrations\Adjust;

use Integra\Domain\Integration\Adjust\Operation\Verification\Handler\VerificationAdjustHandler;
use Integra\Domain\Integration\Common\OperationHandlerInterface;
use Integra\Infrastructure\Environment\Env;
use Integra\Infrastructure\Http\Transport;
use Integra\Infrastructure\Queue\Exception\LimitedRetryJobException;
use Integra\Infrastructure\Queue\Job\Integrations\AbstractIntegrationJob;
use Integra\Infrastructure\Queue\Job\Integrations\IntegrationJobInterface;
use Integra\Models\Ubet\User;
use yii\helpers\StringHelper;
use yii\queue\Queue;
use Yii;

class AdjustVerificationJob extends AbstractIntegrationJob implements IntegrationJobInterface
{
    public int $userId;
    public ?int $partnerId;
    public ?int $offerId;
    public ?int $timeoutSeconds = null;

    /**
     * @inheritDoc
     */
    public function getQueueComponent(): Queue
    {
        return Yii::$app->queue_triggers_adjust;
    }

    /**
     * @inheritDoc
     */
    public function getPushDelaySeconds(): int
    {
        return (int)(new Env('UB_FIRST_TRY_DELAY'))->value();
    }

    /**
     * @inheritDoc
     */
    protected function getRawParams(): array
    {
        $user = User::findOne($this->userId);

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
            'userId' => $user->user_id,
            'partnerId' => $this->partnerId,
            'offerId' => $this->offerId,
            'deviceReg' => $user->device_reg ?? null,
            'timeoutSeconds' => $this->timeoutSeconds,
        ];
    }

    /**
     * @inheritDoc
     */
    protected function createHandler(Transport $transport): OperationHandlerInterface
    {
        return new VerificationAdjustHandler(
            transport: $transport
        );
    }

}
<?php

namespace Integra\Infrastructure\Queue\Job\Integrations\MindBox;

use Integra\Domain\Integration\Common\OperationHandlerInterface;
use Integra\Domain\Integration\MindBox\Operation\UpdateBonusStatus\Handler\UpdateBonusStatusHandler;
use Integra\Infrastructure\Environment\Env;
use Integra\Infrastructure\Http\Transport;
use Integra\Infrastructure\Queue\Exception\LimitedRetryJobException;
use Integra\Infrastructure\Queue\Job\Integrations\AbstractIntegrationJob;
use Integra\Infrastructure\Queue\Job\Integrations\IntegrationJobInterface;
use Integra\Models\Ubet\UserTotoBonus;
use yii\helpers\StringHelper;
use yii\queue\Queue;
use Yii;

class MindBoxBonusStatusJob extends AbstractIntegrationJob implements IntegrationJobInterface
{
    public int $bonusId;
    public int $statusId;
    public ?string $executedDateTimeUtc = null;
    public ?int $timeoutSeconds = null;
    /**
     * @inheritDoc
     */
    public function getQueueComponent(): Queue
    {
        return Yii::$app->queue_triggers_mindbox;
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
        $userTotoBonus = UserTotoBonus::findOne(['bonus_id' => $this->bonusId]);
        if(empty($userTotoBonus)){
            $message = sprintf(
                '[%s] UserTotoBonus not found:: bonusId=%s',
                StringHelper::basename(static::class),
                $this->bonusId
            );
            Yii::error($message, __METHOD__);
            throw new LimitedRetryJobException($message);
        }

        return [
            'bonusId' => $userTotoBonus->bonus_id,
            'statusId' => $this->statusId,
            'executedDateTimeUtc' => $this->executedDateTimeUtc,
            'timeoutSeconds' => $this->timeoutSeconds,
        ];
    }

    /**
     * @inheritDoc
     */
    protected function createHandler(Transport $transport): OperationHandlerInterface
    {
        return new UpdateBonusStatusHandler(
            $transport
        );
    }

}
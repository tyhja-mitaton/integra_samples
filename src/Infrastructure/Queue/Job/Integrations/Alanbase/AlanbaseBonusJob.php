<?php

namespace Integra\Infrastructure\Queue\Job\Integrations\Alanbase;

use Integra\Domain\Integration\Alanbase\Operation\Bonus\Handler\BonusHandler;
use Integra\Domain\Integration\Common\OperationHandlerInterface;
use Integra\Infrastructure\Environment\Env;
use Integra\Infrastructure\Http\Transport;
use Integra\Infrastructure\Queue\Exception\LimitedRetryJobException;
use Integra\Infrastructure\Queue\Job\Integrations\AbstractIntegrationJob;
use Integra\Infrastructure\Queue\Job\Integrations\IntegrationJobInterface;
use Integra\Models\Ubet\UserTotoBonus;
use yii\helpers\StringHelper;
use yii\queue\Queue;
use Yii;

class AlanbaseBonusJob extends AbstractIntegrationJob implements IntegrationJobInterface
{
    public string $clickId;
    public int    $bonusId;
    public ?int $timeoutSeconds = null;

    /**
     * @inheritDoc
     */
    public function getQueueComponent(): Queue
    {
        return Yii::$app->queue_triggers_alanbase;
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
                '[%s] Bonus not found:: %s',
                StringHelper::basename(static::class),
                $this->bonusId
            );
            Yii::error($message, __METHOD__);
            throw new LimitedRetryJobException($message);
        }

        return [
            'clickId' => $this->clickId,
            'bonusId' => $userTotoBonus->bonus_id,
            'timeoutSeconds' => $this->timeoutSeconds,
        ];
    }

    /**
     * @inheritDoc
     */
    protected function createHandler(Transport $transport): OperationHandlerInterface
    {
        return new BonusHandler(
            $transport
        );
    }
}
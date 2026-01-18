<?php

namespace Integra\Infrastructure\Queue\Job\Integrations\MindBox;

use DomainException;
use Integra\Domain\Integration\Common\OperationHandlerInterface;
use Integra\Domain\Integration\MindBox\Operation\ChangeBetStatus\Handler\ChangeBetStatusHandler;
use Integra\Infrastructure\Environment\Env;
use Integra\Infrastructure\Http\Transport;
use Integra\Infrastructure\Queue\Job\Integrations\AbstractIntegrationJob;
use Integra\Infrastructure\Queue\Job\Integrations\IntegrationJobInterface;
use Integra\Models\Ubet\SportBet;
use yii\helpers\StringHelper;
use yii\queue\Queue;
use Yii;

class MindBoxBetStatusJob extends AbstractIntegrationJob implements IntegrationJobInterface
{
    public int $betId;
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
        $bet = SportBet::findOne(['bet_id' => $this->betId]);

        if (!$bet) {
            $message = sprintf(
                '[%s] Bet not found:: %s',
                StringHelper::basename(self::class),
                $this->betId
            );
            Yii::error($message, __METHOD__);
            throw new DomainException($message);
        }

        return [
            'betId' => $bet->bet_id,
            'timeoutSeconds' => $this->timeoutSeconds,
        ];
    }

    /**
     * @inheritDoc
     */
    protected function createHandler(Transport $transport): OperationHandlerInterface
    {
        return new ChangeBetStatusHandler(
            $transport
        );
    }
}
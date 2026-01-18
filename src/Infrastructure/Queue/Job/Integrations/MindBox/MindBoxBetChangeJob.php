<?php

namespace Integra\Infrastructure\Queue\Job\Integrations\MindBox;

use Integra\Domain\Integration\Common\OperationHandlerInterface;
use Integra\Domain\Integration\MindBox\Operation\ChangeBet\Handler\ChangeBetHandler;
use Integra\Infrastructure\Environment\Env;
use Integra\Infrastructure\Http\Transport;
use Integra\Infrastructure\Queue\Exception\LimitedRetryJobException;
use Integra\Infrastructure\Queue\Job\Integrations\AbstractIntegrationJob;
use Integra\Infrastructure\Queue\Job\Integrations\IntegrationJobInterface;
use Integra\Models\Ubet\SportBet;
use yii\helpers\StringHelper;
use yii\queue\Queue;
use Yii;

class MindBoxBetChangeJob extends AbstractIntegrationJob implements IntegrationJobInterface
{
    public int $orderNumber;
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
        $bet = SportBet::findOne(['order_number' => $this->orderNumber]);
        if (empty($bet)) {
            $message = sprintf(
                '[%s] Sport Bet not found:: %s',
                StringHelper::basename(static::class),
                $this->orderNumber
            );
            Yii::error($message, __METHOD__);
            throw new LimitedRetryJobException($message);
        }

        return [
            'orderNumber' => $bet->order_number,
            'timeoutSeconds' => $this->timeoutSeconds,
        ];
    }

    /**
     * @inheritDoc
     */
    protected function createHandler(Transport $transport): OperationHandlerInterface
    {
        return new ChangeBetHandler(
            $transport
        );
    }


}
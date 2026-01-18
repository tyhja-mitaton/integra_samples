<?php

namespace Integra\Infrastructure\Queue\Job\Integrations\MindBox;

use Integra\Domain\Integration\Common\OperationHandlerInterface;
use Integra\Domain\Integration\MindBox\Operation\CreateBet\Handler\CreateBetMiniGameHandler;
use Integra\Infrastructure\Environment\Env;
use Integra\Infrastructure\Http\Transport;
use Integra\Infrastructure\Queue\Exception\LimitedRetryJobException;
use Integra\Infrastructure\Queue\Job\Integrations\AbstractIntegrationJob;
use Integra\Infrastructure\Queue\Job\Integrations\IntegrationJobInterface;
use Integra\Models\Ubet\GameBet;
use yii\helpers\StringHelper;
use yii\queue\Queue;
use Yii;

class MindBoxMiniGameBetJob extends AbstractIntegrationJob implements IntegrationJobInterface
{
    public int $gameId;
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
        $bet = GameBet::findOne(['bet_id' => $this->gameId]);
        if (empty($bet)) {
            $message = sprintf(
                '[%s] Game Bet not found:: %s',
                StringHelper::basename(static::class),
                $this->gameId
            );
            Yii::error($message, __METHOD__);
            throw new LimitedRetryJobException($message);
        }

        return [
            'gameId' => $bet->bet_id,
            'timeoutSeconds' => $this->timeoutSeconds,
        ];
    }

    /**
     * @inheritDoc
     */
    protected function createHandler(Transport $transport): OperationHandlerInterface
    {
        return new CreateBetMiniGameHandler(
            $transport
        );
    }

}
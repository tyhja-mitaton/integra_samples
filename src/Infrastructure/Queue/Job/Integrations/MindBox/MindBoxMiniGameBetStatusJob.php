<?php

namespace Integra\Infrastructure\Queue\Job\Integrations\MindBox;

use Integra\Domain\Integration\Common\OperationHandlerInterface;
use Integra\Domain\Integration\MindBox\Operation\ChangeBetStatus\Handler\ChangeBetStatusMiniGameHandler;
use Integra\Infrastructure\Environment\Env;
use Integra\Infrastructure\Http\Transport;
use Integra\Infrastructure\Queue\Job\Integrations\AbstractIntegrationJob;
use Integra\Infrastructure\Queue\Job\Integrations\IntegrationJobInterface;
use Integra\Models\Ubet\GameBet;
use yii\helpers\StringHelper;
use yii\queue\Queue;
use Yii;
use DomainException;

class MindBoxMiniGameBetStatusJob extends AbstractIntegrationJob implements IntegrationJobInterface
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

        if (!$bet) {
            $message = sprintf(
                '[%s] Bet not found:: %s',
                StringHelper::basename(self::class),
                $this->gameId
            );
            Yii::error($message, __METHOD__);
            throw new DomainException($message);
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
        return new ChangeBetStatusMiniGameHandler(
            $transport
        );
    }
}
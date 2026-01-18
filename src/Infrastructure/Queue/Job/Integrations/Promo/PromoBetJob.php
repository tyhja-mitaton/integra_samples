<?php

namespace Integra\Infrastructure\Queue\Job\Integrations\Promo;

use Integra\Domain\Integration\Common\OperationHandlerInterface;
use Integra\Domain\Integration\Promo\Operation\Bet\Handler\BetPromoHandler;
use Integra\Infrastructure\Environment\Env;
use Integra\Infrastructure\Http\Transport;
use Integra\Infrastructure\Queue\Job\Integrations\AbstractIntegrationJob;
use Integra\Infrastructure\Queue\Job\Integrations\IntegrationJobInterface;
use Integra\Models\Ubet\SportBet;
use yii\queue\Queue;
use yii\helpers\StringHelper;
use DomainException;
use Yii;

class PromoBetJob extends AbstractIntegrationJob implements IntegrationJobInterface
{
    public int $betId;
    public ?int $timeoutSeconds = null;

    /**
     * @inheritDoc
     */
    public function getQueueComponent(): Queue
    {
        return Yii::$app->queue_triggers_promo;
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
        $bet = SportBet::findOne($this->betId);
        if (empty($bet)) {
            $message = sprintf(
                '[%s] Bet not found:: %s',
                StringHelper::basename(self::class),
                $this->betId
            );
            Yii::error($message, __METHOD__);
            throw new DomainException($message);
        }

        return [
            'betId' => $this->betId,
            'userId' => $bet->user_id,
            'timeoutSeconds' => $this->timeoutSeconds,
        ];
    }

    /**
     * @inheritDoc
     */
    protected function createHandler(Transport $transport): OperationHandlerInterface
    {
        return new BetPromoHandler(
            $transport
        );
    }

}
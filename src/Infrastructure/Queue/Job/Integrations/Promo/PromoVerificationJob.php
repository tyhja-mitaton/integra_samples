<?php

namespace Integra\Infrastructure\Queue\Job\Integrations\Promo;

use Integra\Domain\Integration\Common\OperationHandlerInterface;
use Integra\Domain\Integration\Promo\Operation\Verification\Handler\VerificationPromoHandler;
use Integra\Infrastructure\Environment\Env;
use Integra\Infrastructure\Http\Transport;
use Integra\Infrastructure\Queue\Job\Integrations\AbstractIntegrationJob;
use Integra\Infrastructure\Queue\Job\Integrations\IntegrationJobInterface;
use yii\queue\Queue;
use Yii;

class PromoVerificationJob extends AbstractIntegrationJob implements IntegrationJobInterface
{
    public int $userId;
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
        return [
            'userId' => $this->userId,
            'timeoutSeconds' => $this->timeoutSeconds,
        ];
    }

    /**
     * @inheritDoc
     */
    protected function createHandler(Transport $transport): OperationHandlerInterface
    {
        return new VerificationPromoHandler(
            $transport
        );
    }


}
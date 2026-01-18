<?php

namespace Integra\Infrastructure\Queue\Job\Integrations\Promo;

use Integra\Domain\Integration\Common\OperationHandlerInterface;
use Integra\Domain\Integration\Promo\Operation\Replenishment\Handler\ReplenishmentPromoHandler;
use Integra\Infrastructure\Environment\Env;
use Integra\Infrastructure\Http\Transport;
use Integra\Infrastructure\Queue\Exception\LimitedRetryJobException;
use Integra\Infrastructure\Queue\Job\Integrations\AbstractIntegrationJob;
use Integra\Infrastructure\Queue\Job\Integrations\IntegrationJobInterface;
use Integra\Models\Ubet\Payment;
use yii\helpers\StringHelper;
use yii\queue\Queue;
use Yii;

class PromoReplenishmentJob extends AbstractIntegrationJob implements IntegrationJobInterface
{
    public int $payId;
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
        $payment = Payment::findOne($this->payId);
        if (empty($payment)) {
            $message = sprintf(
                '[%s] User not found:: %s',
                StringHelper::basename(static::class),
                $this->payId
            );
            Yii::error($message, __METHOD__);
            throw new LimitedRetryJobException($message);
        }
        return [
            'userId' => $payment->user_id,
            'payId' => $payment->pay_id,
            'timeoutSeconds' => $this->timeoutSeconds,
        ];
    }

    /**
     * @inheritDoc
     */
    protected function createHandler(Transport $transport): OperationHandlerInterface
    {
        return new ReplenishmentPromoHandler(
            $transport
        );
    }

}
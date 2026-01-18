<?php

namespace Integra\Infrastructure\Queue\Job\Integrations\AppsFlyer;

use Integra\Domain\Integration\AppsFlyer\Operation\Deposit\Handler\DepositAppsFlyerHandler;
use Integra\Domain\Integration\Common\OperationHandlerInterface;
use Integra\Infrastructure\Environment\Env;
use Integra\Infrastructure\Http\Transport;
use Integra\Infrastructure\Queue\Exception\LimitedRetryJobException;
use Integra\Infrastructure\Queue\Job\Integrations\AbstractIntegrationJob;
use Integra\Infrastructure\Queue\Job\Integrations\IntegrationJobInterface;
use Integra\Models\Ubet\Payment;
use yii\helpers\StringHelper;
use yii\queue\Queue;
use Yii;

class AppsFlyerDepositJob extends AbstractIntegrationJob implements IntegrationJobInterface
{
    public int $paymentId;
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
        $payment = Payment::findOne($this->paymentId);

        if(empty($payment)) {
            $message = sprintf(
                '[%s] Payment not found:: %s',
                StringHelper::basename(static::class),
                $this->paymentId
            );
            Yii::error($message, __METHOD__);
            throw new LimitedRetryJobException($message);
        }

        return [
            'paymentId' => $payment->pay_id,
            'deviceReg' => $payment->user?->device_reg ?? null,
            'timeoutSeconds' => $this->timeoutSeconds,
        ];
    }

    /**
     * @inheritDoc
     */
    protected function createHandler(Transport $transport): OperationHandlerInterface
    {
        return new DepositAppsFlyerHandler(
            transport: $transport
        );
    }

}
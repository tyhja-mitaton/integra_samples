<?php

namespace Integra\Infrastructure\Queue\Job\Integrations\Alanbase;

use Integra\Domain\Integration\Alanbase\Operation\DepositFee\Handler\DepositFeeHandler;
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

class AlanbaseDepositFeeJob extends AbstractIntegrationJob implements IntegrationJobInterface
{
    public string $clickId;
    public int    $payId;
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
            'clickId' => $this->clickId,
            'payId'  => $payment->pay_id,
            'timeoutSeconds' => $this->timeoutSeconds,
        ];
    }

    /**
     * @inheritDoc
     */
    protected function createHandler(Transport $transport): OperationHandlerInterface
    {
        return new DepositFeeHandler(
            $transport
        );
    }
}
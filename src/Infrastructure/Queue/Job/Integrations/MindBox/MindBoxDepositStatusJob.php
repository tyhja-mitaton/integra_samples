<?php

namespace Integra\Infrastructure\Queue\Job\Integrations\MindBox;

use Integra\Domain\Integration\Common\OperationHandlerInterface;
use Integra\Domain\Integration\MindBox\Enum\DepositStatusEnum;
use Integra\Infrastructure\Environment\Env;
use Integra\Infrastructure\Http\Transport;
use Integra\Infrastructure\Queue\Exception\LimitedRetryJobException;
use Integra\Infrastructure\Queue\Job\Integrations\AbstractIntegrationJob;
use Integra\Infrastructure\Queue\Job\Integrations\IntegrationJobInterface;
use Integra\Models\Ubet\Payment;
use yii\helpers\StringHelper;
use yii\queue\Queue;
use Integra\Domain\Integration\MindBox\Operation\UpdateDepositStatus\Handler\UpdateDepositStatusHandler;
use Yii;

class MindBoxDepositStatusJob extends AbstractIntegrationJob implements IntegrationJobInterface
{
    public int $payId;
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
        $payment = Payment::findOne(['pay_id' => $this->payId]);
        if(empty($payment)){
            $message = sprintf(
                '[%s] Payment not found:: %s',
                StringHelper::basename(static::class),
                $this->payId
            );
            Yii::error($message, __METHOD__);
            throw new LimitedRetryJobException($message);
        }

        return [
            'payId' => $payment->pay_id,
            'statusId' => DepositStatusEnum::SUCCESS->value(),
            'timeoutSeconds' => $this->timeoutSeconds,
        ];
    }

    /**
     * @inheritDoc
     */
    protected function createHandler(Transport $transport): OperationHandlerInterface
    {
        return new UpdateDepositStatusHandler(
            $transport
        );
    }
}
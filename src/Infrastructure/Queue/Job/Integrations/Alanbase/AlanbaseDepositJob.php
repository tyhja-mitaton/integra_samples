<?php
declare(strict_types=1);

namespace Integra\Infrastructure\Queue\Job\Integrations\Alanbase;

use Integra\Domain\Integration\Alanbase\Operation\Deposit\Handler\DepositHandler;
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

class AlanbaseDepositJob extends AbstractIntegrationJob implements IntegrationJobInterface
{
    public string $clickId;
    public int    $payId;
    public ?int $timeoutSeconds = null;

    /**
     * @return Queue
     */
    public function getQueueComponent(): Queue
    {
        return Yii::$app->queue_triggers_alanbase;
    }

    /**
     * @return int
     */
    public function getPushDelaySeconds(): int
    {
        return (int)(new Env('UB_FIRST_TRY_DELAY'))->value();
    }

    /**
     * @return array
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
     * @param Transport $transport
     * @return OperationHandlerInterface
     */
    protected function createHandler(Transport $transport): OperationHandlerInterface
    {
        return new DepositHandler(
            $transport
        );
    }
}
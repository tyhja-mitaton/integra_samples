<?php

namespace Integra\Infrastructure\Queue\Job\Triggers;

use Integra\Domain\Services\AlanbaseService;
use Integra\Domain\Services\MindBoxService;
use Integra\Infrastructure\Queue\BaseJob;
use Integra\Infrastructure\Queue\Job\Integrations\Alanbase\AlanbaseCashoutFeeJob;
use Integra\Infrastructure\Queue\Job\Integrations\MindBox\MindBoxDepositJob;
use Integra\Infrastructure\Queue\Job\Integrations\MindBox\MindBoxDepositStatusJob;
use Integra\Models\Ubet\Payment;
use yii\helpers\StringHelper;
use yii\queue\Queue;
use Yii;

class JobTriggerCashoutShare extends BaseJob
{
    public int $paymentId;

    private AlanbaseService $alanbaseService;
    private MindBoxService $mindBoxService;

    public function init(): void
    {
        parent::init();
        $this->alanbaseService = new AlanbaseService();
        $this->mindBoxService = new MindBoxService();
    }

    /**
     * @inheritDoc
     */
    protected function handle(Queue $queue): void
    {
        $this->alnanbaseTrigger();
        $this->mindBoxTrigger();
    }

    private function mindBoxTrigger(): void
    {
        $payment = Payment::findOne(['pay_id' => $this->paymentId]);
        if (!$payment) {
            $message = sprintf(
                '[%s] payId=%s PAYMENT NOT FOUND.',
                StringHelper::basename(static::class),
                $this->paymentId
            );
            Yii::error($message, __METHOD__);

            return;
        }

        if ($this->mindBoxService->reactivateUserIfNotExistMindbox($payment->user_id, true)) {
            $mindboxDepositJob = new MindBoxDepositJob();
            $mindboxDepositJob->payId = $this->payId;
            $mindboxDepositJob->push();

            $mindboxUpdateDepositJob = new MindBoxDepositStatusJob();
            $mindboxUpdateDepositJob->timeoutSeconds = 30;
            $mindboxUpdateDepositJob->payId = $this->payId;
            $mindboxUpdateDepositJob->push();
        }
    }

    private function alnanbaseTrigger()
    {
        $payment = Payment::findOne(['pay_id' => $this->paymentId]);
        if (!$payment) {
            $message = sprintf(
                '[%s] payId=%s PAYMENT NOT FOUND.',
                StringHelper::basename(static::class),
                $this->paymentId
            );
            Yii::error($message, __METHOD__);

            return;
        }

        if ($this->alanbaseService->isAlanbaseUser($payment->user_id)) {
            $data = $this->alanbaseService->getAlanbaseDataByUserId($payment->user_id);

            if($payment->fee != 0) {
                $job = new AlanbaseCashoutFeeJob();
                $job->clickId = $data->clickId;
                $job->paymentId = $this->paymentId;
                $job->push();
            }
        }
    }
}
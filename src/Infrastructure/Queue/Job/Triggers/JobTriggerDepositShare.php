<?php

namespace Integra\Infrastructure\Queue\Job\Triggers;

use Integra\Domain\Services\AdjustService;
use Integra\Domain\Services\AffiseService;
use Integra\Domain\Services\AlanbaseService;
use Integra\Domain\Services\AppsFlyerService;
use Integra\Domain\Services\MindBoxService;
use Integra\Infrastructure\Queue\BaseJob;
use Integra\Infrastructure\Queue\Job\Integrations\Adjust\AdjustFirstTimeDepositJob;
use Integra\Infrastructure\Queue\Job\Integrations\Adjust\AdjustRecurringDepositJob;
use Integra\Infrastructure\Queue\Job\Integrations\Affise\AffiseDepositJob;
use Integra\Infrastructure\Queue\Job\Integrations\Affise\AffiseFirstDepositJob;
use Integra\Infrastructure\Queue\Job\Integrations\Alanbase\AlanbaseDepositFeeJob;
use Integra\Infrastructure\Queue\Job\Integrations\Alanbase\AlanbaseDepositJob;
use Integra\Infrastructure\Queue\Job\Integrations\AppsFlyer\AppsFlyerDepositJob;
use Integra\Infrastructure\Queue\Job\Integrations\MindBox\MindBoxDepositJob;
use Integra\Infrastructure\Queue\Job\Integrations\MindBox\MindBoxDepositStatusJob;
use Integra\Infrastructure\Queue\Job\Integrations\Promo\PromoDepositJob;
use Integra\Infrastructure\Queue\Job\Integrations\Promo\PromoReplenishmentJob;
use yii\helpers\StringHelper;
use yii\queue\Queue;
use Integra\Models\Ubet\Payment;
use Yii;

class JobTriggerDepositShare extends BaseJob
{
    public int $payId;
    public ?int $partnerId;
    public ?int $offerId;

    private AlanbaseService $alanbaseService;
    private MindBoxService $mindBoxService;
    private AdjustService $adjustService;
    private AffiseService $affiseService;
    private AppsFlyerService $appsFlyerService;

    public function init(): void
    {
        parent::init();
        $this->alanbaseService = new AlanbaseService();
        $this->mindBoxService = new MindBoxService();
        $this->adjustService = new AdjustService();
        $this->affiseService = new AffiseService();
        $this->appsFlyerService = new AppsFlyerService();
    }

    /**
     * @inheritDoc
     */
    protected function handle(Queue $queue): void
    {
        $this->alnanbaseTrigger();
        $this->mindBoxTrigger();
        $this->adjustTrigger();
        $this->affiseTrigger();
        $this->promoTrigger();
        $this->appsFlyerTrigger();
    }

    private function mindBoxTrigger(): void
    {
        $payment = Payment::findOne(['pay_id' => $this->payId]);
        if (!$payment) {
            $message = sprintf(
                '[%s] userId=%s PAYMENT NOT FOUND.',
                StringHelper::basename(static::class),
                $this->payId
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

    private function adjustTrigger(): void
    {
        $payment = Payment::findOne(['pay_id' => $this->payId]);
        if (!$payment) {
            $message = sprintf(
                '[%s] userId=%s PAYMENT NOT FOUND.',
                StringHelper::basename(static::class),
                $this->payId
            );
            Yii::error($message, __METHOD__);

            return;
        }

        $alanbaseDataForAdjust = $this->adjustService->isAlanbaseUser($payment->user_id)
            ? $this->adjustService->getAlanbaseDataByUserId($payment->user_id)
            : null;

        if (empty($alanbaseDataForAdjust)) {
            $message = sprintf(
                '[%s] user NOT success CheckAlanbaseUser. userId=%s: , paymentId=%s.',
                StringHelper::basename(static::class),
                $payment->user_id,
                $this->payId
            );
            Yii::info($message, __METHOD__);
        }

        $firstStatusOkDeposit = Payment::find()->orderBy(['pay_id' => SORT_ASC])
            ->where(['user_id' => $payment->user_id, 'status' => 'OK', 'type' => 'IN'])
            ->andWhere(['>', 'amount', 0])->one();

        if($firstStatusOkDeposit && $firstStatusOkDeposit->pay_id === $payment->pay_id) {
            $job = new AdjustFirstTimeDepositJob();
        } else {
            $job = new AdjustRecurringDepositJob();
        }
        $job->userId = (int)$payment->user_id;
        $job->paymentId = (int)$this->payId;
        $job->partnerId = $this->partnerId;
        $job->offerId = $this->offerId;
        $job->push();

    }

    private function alnanbaseTrigger()
    {
        $payment = Payment::findOne(['pay_id' => $this->payId]);
        if (!$payment) {
            $message = sprintf(
                '[%s] payId=%s PAYMENT NOT FOUND.',
                StringHelper::basename(static::class),
                $this->payId
            );
            Yii::error($message, __METHOD__);

            return;
        }

        if ($this->alanbaseService->isAlanbaseUser($payment->user_id)) {
            $data = $this->alanbaseService->getAlanbaseDataByUserId($payment->user_id);

            $job = new AlanbaseDepositJob();
            $job->clickId = $data->clickId;
            $job->payId = $this->payId;
            $job->push();

            if (!empty($payment->fee)) {
                $job = new AlanbaseDepositFeeJob();
                $job->clickId = $data->clickId;
                $job->payId = $this->payId;
                $job->push();
            }
        }
    }

    private function affiseTrigger(): void
    {
        $payment = Payment::findOne(['pay_id' => $this->payId]);
        if (!$payment) {
            $message = sprintf(
                '[%s] payId=%s PAYMENT NOT FOUND.',
                StringHelper::basename(static::class),
                $this->payId
            );
            Yii::error($message, __METHOD__);

            return;
        }

        $firstDeposit = Payment::find()->orderBy(['pay_id' => SORT_ASC])
            ->where(['user_id' => $payment->user_id, 'type' => 'IN'])
            ->andWhere(['>', 'amount', 0])->one();

        if ($this->affiseService->isAffiseUser($payment->user_id)) {
            $affiseData = $this->affiseService->getAffiseDataByUserId($payment->user_id);

            if ($firstDeposit && $firstDeposit->pay_id == $payment->pay_id) {
                $job = new AffiseFirstDepositJob();
            } else {
                $job = new AffiseDepositJob();
            }

            $job->affiseDeviceId = $affiseData->affiseDeviceId;
            $job->paymentId = $payment->pay_id;
            $job->push();
        }
    }

    private function promoTrigger(): void
    {
        $job = new PromoDepositJob();
        $job->payId = $this->payId;
        $job->push();

        $job = new PromoReplenishmentJob();
        $job->payId = $this->payId;
        $job->push();
    }

    private function appsflyerTrigger(): void
    {
        $payment = Payment::findOne($this->payId);
        if (!$payment) {
            $message = sprintf(
                '[%s] payId=%s PAYMENT NOT FOUND.',
                StringHelper::basename(static::class),
                $this->payId
            );
            Yii::error($message, __METHOD__);

            return;
        }

        if($this->appsFlyerService->isAppsFlyerMobileUser($payment->user_id)) {
            $job = new AppsFlyerDepositJob();
            $job->paymentId = $this->payId;
            $job->push();
        }
    }
}
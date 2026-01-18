<?php

declare(strict_types=1);

namespace share\jobs;

use Yii;
use yii\helpers\StringHelper;
use Integra\Infrastructure\Queue\BaseJob;
use Integra\Models\Ubet\UsersAuthHistory;
use Integra\Domain\Services\AffiseService;
use Integra\Domain\Services\AdjustService;
use Integra\Domain\Services\AlanbaseService;
use Integra\Infrastructure\Queue\Job\Integrations\MindBox\MindBoxAuthJob;
use Integra\Infrastructure\Queue\Job\Integrations\Promo\PromoRegistrationJob;
use Integra\Infrastructure\Queue\Job\Integrations\Affise\AffiseRegistrationJob;
use Integra\Infrastructure\Queue\Job\Integrations\Adjust\AdjustRegistrationJob;
use Integra\Infrastructure\Queue\Job\Integrations\MindBox\MindBoxRegistrationJob;
use Integra\Infrastructure\Queue\Job\Integrations\Alanbase\AlanbaseRegistrationJob;
use Integra\Infrastructure\Queue\Job\Integrations\MindBox\MindBoxRegistrationNationalSportExpertJob;

class JobTriggerRegistrationUserShare extends BaseJob
{
    public int $userId;
    public ?string $landing = null;
    //todo не забыть добавить эти поля в сервисе который будет вызывать этот Job
    public ?string $adjustAdid = null;
    public ?string $gpsAdid = null;
    public ?string $idfa = null;
    public ?string $idfv = null;
    private const NATIONAL_SPORT_EXPERT_LANDING_PREFIX_NSE = 'nse-';
    private const NATIONAL_SPORT_EXPERT_LANDING_PREFIX_LSE = 'lse-';

    private AlanbaseService $alanbaseService;
    private AffiseService $affiseService;
    private AdjustService $adjustService;

    public function init(): void
    {
        parent::init();
        $this->alanbaseService = new AlanbaseService();
        $this->affiseService = new AffiseService();
        $this->adjustService = new AdjustService();
    }

    protected function handle($queue): void
    {
        $this->alnanbaseTrigger();
        $this->mindBoxTrigger();
        $this->affiseTrigger();
        $this->promoTrigger();
        $this->adjustTrigger();
    }

    private function alnanbaseTrigger(): void
    {
        if ($this->alanbaseService->isAlanbaseUser($this->userId)) {
            $data = $this->alanbaseService->getAlanbaseDataByUserId($this->userId);

            $job = new AlanbaseRegistrationJob();
            $job->clickId = $data->clickId;
            $job->userId = $data->userId;
            $job->push();
        }
    }

    private function mindBoxTrigger(): void
    {
        if ($this->landing && (
                str_contains($this->landing, self::NATIONAL_SPORT_EXPERT_LANDING_PREFIX_NSE)
                || str_contains($this->landing, self::NATIONAL_SPORT_EXPERT_LANDING_PREFIX_LSE)
            )
        ) {
            $mindBoxRegistrationJob = new MindBoxRegistrationNationalSportExpertJob();
        } else {
            $mindBoxRegistrationJob = new MindBoxRegistrationJob();
        }
        $mindBoxRegistrationJob->userId = $this->userId;
        $mindBoxRegistrationJob->landing = $this->landing;
        $mindBoxRegistrationJob->push();

        $userAuthHistory = UsersAuthHistory::findOne(['user_id' => $this->userId]);

        if ($userAuthHistory !== null) {
            $mindBoxAuthJob = new MindBoxAuthJob();
            $mindBoxAuthJob->userAuthHistory = $userAuthHistory;
            //todo смысл?
            $mindBoxAuthJob->userAgent = '';
            $mindBoxAuthJob->push();
        }
    }

    private function affiseTrigger(): void
    {
        if ($this->affiseService->isAffiseUser($this->userId)) {
            $affiseData = $this->affiseService->getAffiseDataByUserId($this->userId);

            $job = new AffiseRegistrationJob();
            $job->affiseDeviceId = $affiseData->affiseDeviceId;
            $job->userId = $affiseData->userId;
            $job->push();
        }
    }

    private function promoTrigger(): void
    {
        $job = new PromoRegistrationJob();
        $job->userId = $this->userId;
        $job->push();
    }

    private function adjustTrigger(): void
    {
        if ($this->adjustAdid || $this->gpsAdid || $this->idfa || $this->idfv) {
            $alanbaseDataForAdjust = $this->adjustService->isAlanbaseUser($this->userId)
                ? $this->adjustService->getAlanbaseDataByUserId($this->userId)
                : null;

            if (empty($alanbaseDataForAdjust)) {
                $message = sprintf(
                    '[%s] userId=%s NOT passed isAlanbaseUser for adjust. $adjustIdentifiers=%s',
                    StringHelper::basename(static::class),
                    $this->userId,
                    json_encode([
                        'adjust_adid' => $this->adjustAdid,
                        'gps_adid' => $this->gpsAdid,
                        'idfa' => $this->idfa,
                        'idfv' => $this->idfv,
                    ])
                );
                Yii::info($message, __METHOD__);
            }

            $job = new AdjustRegistrationJob();
            $job->userId = $this->userId;
            $job->adjustAdid = $this->adjustAdid;
            $job->gpsAdid = $this->gpsAdid;
            $job->idfa = $this->idfa;
            $job->idfv = $this->idfv;
            $job->offerId = $alanbaseDataForAdjust?->offerId;
            $job->partnerId = $alanbaseDataForAdjust?->partnerId;
            $job->push();
        }
    }
}

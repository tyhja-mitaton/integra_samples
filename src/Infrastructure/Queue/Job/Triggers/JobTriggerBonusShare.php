<?php

namespace Integra\Infrastructure\Queue\Job\Triggers;

use Integra\Domain\Services\AlanbaseService;
use Integra\Domain\Services\MindBoxService;
use Integra\Infrastructure\Queue\BaseJob;
use Integra\Infrastructure\Queue\Job\Integrations\Alanbase\AlanbaseBonusJob;
use Integra\Infrastructure\Queue\Job\Integrations\MindBox\MindBoxBonusJob;
use Integra\Models\Ubet\UserTotoBonus;
use yii\helpers\StringHelper;
use yii\queue\Queue;
use Yii;

class JobTriggerBonusShare extends BaseJob
{
    public int $bonusId;
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
        $userTotoBonus = UserTotoBonus::findOne(['bonus_id' => $this->bonusId]);
        if(empty($userTotoBonus)){
            $message = sprintf(
                '[%s] bonusId=%s UserTotoBonus not found.',
                StringHelper::basename(static::class),
                $this->bonusId
            );
            Yii::error($message, __METHOD__);

            return;
        }

        if ($this->mindBoxService->reactivateUserIfNotExistMindbox($userTotoBonus->user_id, true)) {
            $mindboxBonusJob = new MindBoxBonusJob();
            $mindboxBonusJob->bonusId = $this->bonusId;
            $mindboxBonusJob->push();
        }
    }

    private function alnanbaseTrigger()
    {
        $userTotoBonus = UserTotoBonus::findOne(['bonus_id' => $this->bonusId]);
        if(empty($userTotoBonus)){
            $message = sprintf(
                '[%s] bonusId=%s UserTotoBonus not found.',
                StringHelper::basename(static::class),
                $this->bonusId
            );
            Yii::error($message, __METHOD__);

            return;
        }

        if ($this->alanbaseService->isAlanbaseUser($userTotoBonus->user_id)) {
            $data = $this->alanbaseService->getAlanbaseDataByUserId($userTotoBonus->user_id);
            $alanbaseBonusJob = new AlanbaseBonusJob();
            $alanbaseBonusJob->clickId = $data->clickId;
            $alanbaseBonusJob->bonusId = $this->bonusId;
            $alanbaseBonusJob->push();
        }
    }
}
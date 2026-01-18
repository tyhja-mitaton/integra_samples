<?php

namespace Integra\Infrastructure\Queue\Job\Triggers;

use Integra\Domain\Services\MindBoxService;
use Integra\Infrastructure\Queue\BaseJob;
use Integra\Infrastructure\Queue\Job\Integrations\MindBox\MindBoxBonusStatusJob;
use Integra\Models\Ubet\UserTotoBonus;
use yii\helpers\StringHelper;
use yii\queue\Queue;
use Yii;

class JobTriggerBonusStatusShare extends BaseJob
{
    public int $bonusId;
    public int $statusId;
    public ?string $executedDateTimeUtc = null;
    private MindBoxService $mindBoxService;

    public function init(): void
    {
        parent::init();
        $this->mindBoxService = new MindBoxService();
    }

    /**
     * @inheritDoc
     */
    protected function handle(Queue $queue): void
    {
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
            $mindboxBonusStatusJob = new MindBoxBonusStatusJob();
            $mindboxBonusStatusJob->bonusId = $this->bonusId;
            $mindboxBonusStatusJob->statusId = $this->statusId;
            $mindboxBonusStatusJob->executedDateTimeUtc = $this->executedDateTimeUtc;
            $mindboxBonusStatusJob->push();
        }
    }
}
<?php

namespace Integra\Infrastructure\Queue\Job\Triggers;

use Integra\Infrastructure\Queue\BaseJob;
use Integra\Infrastructure\Queue\Job\Integrations\Promo\PromoNewStatusJob;
use yii\queue\Queue;

class JobTriggerNewStatusShare extends BaseJob
{
    public int $userId;
    public int $statusId;

    /**
     * @inheritDoc
     */
    protected function handle(Queue $queue): void
    {
        $this->promoTrigger();
    }

    private function promoTrigger(): void
    {
        $job = new PromoNewStatusJob();
        $job->userId = $this->userId;
        $job->statusId = $this->statusId;
        $job->push();
    }
}
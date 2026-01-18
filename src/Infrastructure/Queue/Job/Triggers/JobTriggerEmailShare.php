<?php

namespace Integra\Infrastructure\Queue\Job\Triggers;

use Integra\Infrastructure\Queue\BaseJob;
use Integra\Infrastructure\Queue\Job\Integrations\Promo\PromoEmailJob;
use yii\queue\Queue;

class JobTriggerEmailShare extends BaseJob
{
    public int $userId;

    /**
     * @inheritDoc
     */
    protected function handle(Queue $queue): void
    {
        $this->promoTrigger();
    }

    private function promoTrigger(): void
    {
        $job = new PromoEmailJob();
        $job->userId = $this->userId;
        $job->push();
    }
}
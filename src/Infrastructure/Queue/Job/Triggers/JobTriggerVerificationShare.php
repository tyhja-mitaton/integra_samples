<?php

namespace Integra\Infrastructure\Queue\Job\Triggers;

use Integra\Infrastructure\Queue\BaseJob;
use Integra\Infrastructure\Queue\Job\Integrations\Adjust\AdjustVerificationJob;
use Integra\Infrastructure\Queue\Job\Integrations\Promo\PromoVerificationJob;
use yii\queue\Queue;

class JobTriggerVerificationShare extends BaseJob
{
    public int $userId;
    public bool $act;

    /**
     * @inheritDoc
     */
    protected function handle(Queue $queue): void
    {
        $this->promoTrigger();
        $this->adjustTrigger();
    }

    private function promoTrigger(): void
    {
        if($this->act) {
            $job = new PromoVerificationJob();
            $job->userId = $this->userId;
            $job->push();
        }
    }

    private function adjustTrigger(): void
    {
        if($this->act) {
            $job = new AdjustVerificationJob();
            $job->userId = $this->userId;
            $job->push();
        }
    }
}
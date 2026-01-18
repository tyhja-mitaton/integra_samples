<?php

namespace Integra\Infrastructure\Queue\Job\Triggers;

use Integra\Infrastructure\Queue\BaseJob;
use Integra\Infrastructure\Queue\Job\Integrations\MindBox\MindBoxEditUserJob;
use yii\queue\Queue;

class JobTriggerEditUserShare extends BaseJob
{
    public int $userId;
    public ?string $landing = null;

    /**
     * @inheritDoc
     */
    protected function handle(Queue $queue): void
    {
        $mindboxEditUserJob = new MindBoxEditUserJob();
        $mindboxEditUserJob->userId = $this->userId;
        $mindboxEditUserJob->landing = $this->landing;
        $mindboxEditUserJob->push();
    }
}
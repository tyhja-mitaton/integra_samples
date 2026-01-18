<?php
declare(strict_types=1);

namespace Integra\Infrastructure\Queue\Job\Integrations;

use yii\queue\Queue;

interface IntegrationJobInterface
{
    /**
     * Ставим задачу в очередь.
     */
    public function push(): void;

    /**
     * Компонент очереди, в которую ставим.
     */
    public function getQueueComponent(): Queue;

    /**
     * Delay перед первым выполнением (в секундах).
     */
    public function getPushDelaySeconds(): int;
}

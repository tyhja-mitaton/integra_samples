<?php
declare(strict_types=1);

namespace Integra\Infrastructure\Queue;

use Yii;
use Throwable;
use yii\queue\Queue;
use yii\base\BaseObject;
use yii\queue\JobInterface;
use yii\helpers\StringHelper;
use Integra\Domain\Integration\TempLocalDebug;

/**
 * Базовый Job не имеющих retry
 */
abstract class BaseJob extends BaseObject implements JobInterface
{
    /**
     * @param Queue $queue
     * @return void
     */
    public function execute($queue): void
    {
        $jobName = StringHelper::basename(static::class);
        $encodedPublicParams = json_encode(get_object_vars($this), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        $message = sprintf(
            '[%s] Job started. With public params: %s',
            $jobName,
            $encodedPublicParams
        );
        Yii::debug($message, 'queue');
        TempLocalDebug::echo($message);

        try {
            $this->handle($queue);
            $message = sprintf(
                '[%s] ✅ Job finished successfully. Public params: %s',
                $jobName,
                $encodedPublicParams
            );
            Yii::debug($message, 'queue');
            TempLocalDebug::echo($message);
        } catch (Throwable $e) {
            $message = sprintf(
                '[%s] ❌ Job failed. Public params: %s. Error: %s',
                $jobName,
                $encodedPublicParams,
                $e->getMessage()
            );
            Yii::error($message, 'queue');
            TempLocalDebug::echo($message);
        }
    }

    /**
     * @param Queue $queue
     * @return void
     * @throws Throwable
     */
    abstract protected function handle(Queue $queue): void;
}

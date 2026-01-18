<?php
declare(strict_types=1);

namespace Integra\Infrastructure\Queue;

use Yii;
use Throwable;
use DomainException;
use yii\queue\Queue;
use yii\base\BaseObject;
use yii\queue\ExecEvent;
use yii\helpers\StringHelper;
use yii\queue\RetryableJobInterface;
use Integra\Infrastructure\Environment\Env;
use Integra\Domain\Integration\TempLocalDebug;

abstract class BaseRetryableJob extends BaseObject implements RetryableJobInterface
{
    /** @var int */
    public int $attempt = 1;

    /** @var array<int, bool> */
    private static array $subscribedQueues = [];

    public function execute($queue): void
    {
        $jobName = StringHelper::basename(static::class);
        $encodedParams = $this->buildEncodedParams();

        $msgStart = sprintf(
            "[%s] attempt=%d Job started. Public params: %s",
            $jobName,
            $this->attempt,
            $encodedParams
        );
        Yii::debug($msgStart, 'queue');
        TempLocalDebug::echo($msgStart);

        $this->attachAfterErrorHandler($queue);

        $this->handle($queue);

        $msgOk = sprintf(
            "[%s] attempt=%d ✅ Job finished successfully. Public params: %s",
            $jobName,
            $this->attempt,
            $encodedParams
        );
        Yii::debug($msgOk, 'queue');
        TempLocalDebug::echo($msgOk);
    }

    /**
     * @param $queue
     * @return void
     */
    abstract protected function handle($queue): void;

    /**
     * Подписываем очередь на afterError только один раз
     * @param Queue $queue
     * @return void
     */
    private function attachAfterErrorHandler(Queue $queue): void
    {
        $key = $queue->queueName . '::' . static::class;
        if (isset(self::$subscribedQueues[$key])) {
            return;
        }
        $queue->on(Queue::EVENT_AFTER_ERROR, [$this, 'onAfterError']);
        self::$subscribedQueues[$key] = true;
    }

    /**
     * @param ExecEvent $event
     * @return void
     */
    public function onAfterError(ExecEvent $event): void
    {
        $job = $event->job;
        $error = $event->error;

        if (!$job instanceof self) {
            return;
        }

        $event->retry = false;

        if (!$job->canRetry($job->attempt, $error)) {
            return;
        }

        $delay = $this->calculateDelay($error);
        $this->logRetry($job, $delay, $error);

        $job->attempt++;

        $event->sender
            ->delay($delay)
            ->push($job);
    }

    /**
     * @param Throwable $error
     * @return int
     */
    protected function calculateDelay(Throwable $error): int
    {
        $httpDelay = (int)(new Env('UB_HTTP_RETRY_DELAY'))->value();
        $runtimeDelay = (int)(new Env('UB_RUNTIME_RETRY_DELAY'))->value();

        return $error instanceof DomainException
            ? $httpDelay
            : $runtimeDelay;
    }

    /**
     * @param Throwable $error
     * @return int
     */
    protected function getMaxAttempts(Throwable $error): int
    {
        return $error instanceof DomainException
            ? (int)(new Env('UB_JOB_MAX_HTTP_ATTEMPTS'))->value()
            : (int)(new Env('UB_JOB_MAX_RUNTIME_ATTEMPTS'))->value();
    }

    /**
     * @param BaseRetryableJob $job
     * @param int $delay
     * @param Throwable $error
     * @return void
     */
    protected function logRetry(self $job, int $delay, Throwable $error): void
    {
        $message = sprintf(
            ">>>%s retry №(%d) job %s due to a %s error; delay=%d; with public params:\n%s\n, exception message=%s\n",
            $job->attempt === $this->getMaxAttempts($error)
                ? 'Last trying'
                : 'Trying',
            $job->attempt,
            StringHelper::basename(static::class),
            $error instanceof DomainException ? 'logical' : 'infrastructure',
            $delay,
            $this->buildEncodedParams(),
            $error->getMessage());
        Yii::warning($message, __METHOD__);
        TempLocalDebug::echo($message);
    }

    /**
     * @return int
     */
    public function getTtr(): int
    {
        return (int)(new Env('UB_JOB_TTR'))->value();
    }

    /**
     * @param $attempt
     * @param $error
     * @return bool
     */
    public function canRetry($attempt, $error): bool
    {
        $max = $this->getMaxAttempts($error);

        if ($error instanceof DomainException) {
            return $attempt <= $max;
        }

        return $max === 0 || $attempt <= $max;
    }

    /**
     * @return string
     */
    private function buildEncodedParams(): string
    {
        return json_encode(
            $this->buildPublicParams(),
            JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT
        );
    }

    /**
     * Убирает из параметров объекта лишние данные
     * @return array
     */
    private function buildPublicParams(): array
    {
        $all = get_object_vars($this);
        unset($all['attempt']);

        return array_filter(
            $all,
            function ($value) {
                if (is_object($value)) {
                    $class = get_class($value);
                    if (str_starts_with($class, 'Integra\\Domain\\Services\\')) {
                        return false;
                    }
                }
                return true;
            }
        );
    }
}

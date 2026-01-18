<?php

namespace Integra\Infrastructure\Queue\Job\Integrations;

use Yii;
use Throwable;
use yii\queue\Queue;
use GuzzleHttp\Client;
use yii\helpers\StringHelper;
use Integra\Infrastructure\Generic\Result;
use Integra\Infrastructure\Http\Transport;
use Integra\Domain\Integration\TempLocalDebug;
use Integra\Infrastructure\Queue\BaseRetryableJob;
use Integra\Infrastructure\Generic\Response\ServerError;
use Integra\Infrastructure\Http\Transport\LoggingTransport;
use Integra\Domain\Integration\Common\OperationHandlerInterface;
use Integra\Infrastructure\Http\Transport\ModifiedHttpTransport;
use Integra\Infrastructure\Queue\Exception\EndlessRetryJobException;
use Integra\Infrastructure\Queue\Exception\LimitedRetryJobException;

/**
 * Базовый класс для интеграционных Job.
 */
abstract class AbstractIntegrationJob extends BaseRetryableJob implements IntegrationJobInterface
{
    /** @var Result|null */
    private ?Result $lastResult = null;

    /**
     * @return void
     */
    final public function push(): void
    {
        $queue = $this->getQueueComponent();
        $delay = $this->getPushDelaySeconds();

        try {
            $queue->delay($delay)->push($this);
            $message = sprintf(
                '[%s] pushed successfully to %s queue with delay=%ds',
                StringHelper::basename(static::class),
                $queue->queueName,
                $delay
            );
            Yii::info($message, __METHOD__);
        } catch (Throwable $e) {
            $message = sprintf(
                '[%s] Failed to push to %s queue: %s',
                StringHelper::basename(static::class),
                $queue->queueName,
                $e->getMessage()
            );
            Yii::error($message, __METHOD__);
            throw new EndlessRetryJobException($e->getMessage(), 0, $e);
        }
    }

    /**
     * @param $queue
     * @return void
     */
    final public function handle($queue): void
    {
        $rawParams = $this->getRawParams();
        $this->lastResult = $this->runOperation($rawParams);

        $message = sprintf(
            '[%s] ✅ Job succeeded, params=%s',
            StringHelper::basename(static::class),
            json_encode($rawParams, JSON_UNESCAPED_UNICODE)
        );
        Yii::info($message, __METHOD__);
        TempLocalDebug::echo($message);

        $this->postSuccess($rawParams);
    }

    /**
     * @param array $params
     * @return Result
     */
    final protected function runOperation(array $params): Result
    {
        $inner = new ModifiedHttpTransport(new Client(['http_errors' => false]));
        $transport = new LoggingTransport($inner);

        $handler = $this->createHandler($transport);
        $result = $handler->execute($params);

        if (!$result->isSuccessful()) {
            $this->handleFailure($result, $params);
        }

        return $result;
    }

    /**
     * Политика retry — Endless для инфраструктурных ошибок и Limited для логических ошибок.
     * Можно переопределить в конкретных Job-ах при необходимости.
     */
    protected function handleFailure(Result $result, array $params): void
    {
        $error = $result->error();
        $code = $error['code'] ?? null;
        $message = $error['message'] ?? json_encode($error, JSON_UNESCAPED_UNICODE);

        if ($code === null || $code >= (new ServerError())->code()) {
            $message = "[{$this->getJobName()}] Infra error code={$code}: {$message}";
            Yii::error($message, __METHOD__);

            throw new EndlessRetryJobException($message);
        }

        $message = "[{$this->getJobName()}] Business error code={$code}: {$message}";
        Yii::error($message, __METHOD__);

        throw new LimitedRetryJobException($message);
    }

    /**
     * Хук: что сделать после успешного выполнения (например, пометка флага).
     */
    protected function postSuccess(array $params): void
    {
    }

    /**
     * Позволяет наследникам при необходимости получить сам Result.
     */
    protected function getLastResult(): ?Result
    {
        return $this->lastResult;
    }

    /**
     * @return array «сырые» параметры для Handler->execute()
     */
    abstract protected function getRawParams(): array;

    /**
     * @param Transport $transport
     * @return OperationHandlerInterface
     */
    abstract protected function createHandler(Transport $transport): OperationHandlerInterface;

    /**
     * Компонент очереди для push().
     */
    abstract public function getQueueComponent(): Queue;

    /**
     * Delay перед постановкой в очередь.
     */
    abstract public function getPushDelaySeconds(): int;

    private function getJobName(): string
    {
        return StringHelper::basename(static::class);
    }
}

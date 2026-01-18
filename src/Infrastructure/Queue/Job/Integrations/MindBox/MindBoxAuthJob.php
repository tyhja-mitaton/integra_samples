<?php

declare(strict_types=1);

namespace Integra\Infrastructure\Queue\Job\Integrations\MindBox;

use Yii;
use Exception;
use yii\queue\Queue;
use Integra\Models\Ubet\UsersAuthHistory;
use Integra\Infrastructure\Http\Transport;
use Integra\Infrastructure\Environment\Env;
use Integra\Domain\Integration\Common\OperationHandlerInterface;
use Integra\Infrastructure\Queue\Job\Integrations\AbstractIntegrationJob;
use Integra\Infrastructure\Queue\Job\Integrations\IntegrationJobInterface;
use Integra\Domain\Integration\MindBox\Operation\AuthorizeCustomer\Handler\AuthorizeCustomerHandler;

final class MindBoxAuthJob extends AbstractIntegrationJob implements IntegrationJobInterface
{
    public UsersAuthHistory $userAuthHistory;
    public ?string $userAgent = null;
    public ?int $timeoutSeconds = null;

    public function getQueueComponent(): Queue
    {
        return Yii::$app->queue_triggers_mindbox;
    }

    public function getPushDelaySeconds(): int
    {
        return (int)(new Env('UB_FIRST_TRY_DELAY'))->value();
    }

    /**
     * @throws Exception
     */
    protected function getRawParams(): array
    {
        return [
            'userAuthHistory' => $this->userAuthHistory,
            'userAgent' => $this->userAgent,
            'timeoutSeconds' => $this->timeoutSeconds,
        ];
    }

    protected function createHandler(Transport $transport): OperationHandlerInterface
    {
        return new AuthorizeCustomerHandler($transport);
    }
}
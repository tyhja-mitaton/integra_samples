<?php

namespace Integra\Infrastructure\Queue\Job\Integrations\MindBox;

use Integra\Domain\Integration\Common\OperationHandlerInterface;
use Integra\Domain\Integration\MindBox\Operation\EditCustomer\Handler\EditCustomerHandler;
use Integra\Domain\Services\User\BetInfoService;
use Integra\Infrastructure\Http\Transport;
use Integra\Domain\Services\User\BackofficePlayerMarksService;
use Integra\Domain\Services\User\BlacklistService;
use Integra\Domain\Services\User\CashbackService;
use Integra\Infrastructure\Environment\Env;
use Integra\Infrastructure\Queue\Exception\LimitedRetryJobException;
use Integra\Infrastructure\Queue\Job\Integrations\AbstractIntegrationJob;
use Integra\Infrastructure\Queue\Job\Integrations\IntegrationJobInterface;
use Integra\Models\Ubet\User;
use yii\helpers\StringHelper;
use yii\queue\Queue;
use Yii;

class MindBoxEditUserJob extends AbstractIntegrationJob implements IntegrationJobInterface
{
    public int $userId;
    public ?string $landing = null;
    public ?int $timeoutSeconds = null;
    public BlacklistService $blacklistService;
    public CashbackService $cashbackService;
    public BackofficePlayerMarksService $playerMarksService;
    public BetInfoService $betInfoService;

    public function init(): void
    {
        parent::init();
        $this->blacklistService = new BlacklistService();
        $this->cashbackService = new CashbackService();
        $this->playerMarksService = new BackofficePlayerMarksService();
        $this->betInfoService = new BetInfoService();
    }

    /**
     * @inheritDoc
     */
    public function getQueueComponent(): Queue
    {
        return Yii::$app->queue_triggers_mindbox;
    }

    /**
     * @inheritDoc
     */
    public function getPushDelaySeconds(): int
    {
        return (int)(new Env('UB_FIRST_TRY_DELAY'))->value();
    }

    /**
     * @inheritDoc
     */
    protected function getRawParams(): array
    {
        $user = User::findOne(['user_id' => $this->userId]);
        if (empty($user)) {
            $message = sprintf(
                '[%s] User not found:: %s',
                StringHelper::basename(static::class),
                $this->userId
            );
            Yii::error($message, __METHOD__);
            throw new LimitedRetryJobException($message);
        }

        return [
            'userId' => $user->user_id,
            'landing' => $this->landing,
            'deviceReg' => $user->device_reg,
            'timeoutSeconds' => $this->timeoutSeconds,
        ];
    }

    /**
     * @inheritDoc
     */
    protected function createHandler(Transport $transport): OperationHandlerInterface
    {
        return new EditCustomerHandler(
            $transport,
            $this->blacklistService,
            $this->cashbackService,
            $this->playerMarksService,
            $this->betInfoService
        );
    }
}
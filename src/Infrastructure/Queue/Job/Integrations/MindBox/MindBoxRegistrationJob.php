<?php

namespace Integra\Infrastructure\Queue\Job\Integrations\MindBox;

use Yii;
use Exception;
use yii\queue\Queue;
use yii\helpers\StringHelper;
use Integra\Models\Ubet\User;
use Integra\Infrastructure\Generic\Result;
use Integra\Infrastructure\Http\Transport;
use Integra\Infrastructure\Environment\Env;
use Integra\Domain\Services\MindBoxService;
use Integra\Domain\Services\User\CashbackService;
use Integra\Domain\Services\User\BlacklistService;
use Integra\Domain\Services\User\BackofficePlayerMarksService;
use Integra\Domain\Integration\Common\OperationHandlerInterface;
use Integra\Infrastructure\Queue\Exception\LimitedRetryJobException;
use Integra\Infrastructure\Queue\Job\Integrations\AbstractIntegrationJob;
use Integra\Infrastructure\Queue\Job\Integrations\IntegrationJobInterface;
use Integra\Domain\Integration\MindBox\Operation\RegisterCustomer\Handler\RegisterCustomerHandler;

class MindBoxRegistrationJob extends AbstractIntegrationJob implements IntegrationJobInterface
{
    public int $userId;
    public ?string $landing = null;
    public ?int $timeoutSeconds = null;
    public MindBoxService $mindBoxService;
    public BlacklistService $blacklistService;
    public CashbackService $cashbackService;
    public BackofficePlayerMarksService $playerMarksService;

    public function init(): void
    {
        parent::init();
        $this->mindBoxService = new MindBoxService();
        $this->blacklistService = new BlacklistService();
        $this->cashbackService = new CashbackService();
        $this->playerMarksService = new BackofficePlayerMarksService();
    }

    /**
     * @return Queue
     */
    public function getQueueComponent(): Queue
    {
        return Yii::$app->queue_triggers_mindbox;
    }

    /**
     * @return int
     */
    public function getPushDelaySeconds(): int
    {
        return (int)(new Env('UB_FIRST_TRY_DELAY'))->value();
    }

    /**
     * @return array
     */
    protected function getRawParams(): array
    {
        //todo AlanbaseRegistrationJob
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
            'deviceReg' => $user->device_reg,
            'landing' => $this->landing,
            'timeoutSeconds' => $this->timeoutSeconds,
        ];
    }

    /**
     * @param Transport $transport
     * @return OperationHandlerInterface
     */
    protected function createHandler(Transport $transport): OperationHandlerInterface
    {
        return new RegisterCustomerHandler(
            $transport,
            $this->blacklistService,
            $this->cashbackService,
            $this->playerMarksService
        );
    }

    /**
     * @param array $params
     * @return void
     * @throws Exception
     */
    protected function postSuccess(array $params): void
    {
        $this->mindBoxService->markUserAsExistedInMindBoxSystem($params['userId']);
    }

    /**
     * При желании можно тут переопределить, чтобы изменить логику retry/логирования в этом конкретном Job-е если это потребуется.
     */
    protected function handleFailure(Result $result, array $params): void
    {
        parent::handleFailure($result, $params);
    }
}

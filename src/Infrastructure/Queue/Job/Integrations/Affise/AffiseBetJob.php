<?php

namespace Integra\Infrastructure\Queue\Job\Integrations\Affise;

use Integra\Domain\Integration\Affise\Operation\Bet\Handler\BetHandler;
use Integra\Domain\Integration\Common\OperationHandlerInterface;
use Integra\Domain\Services\AffiseService;
use Integra\Infrastructure\Environment\Env;
use Integra\Infrastructure\Http\Transport;
use Integra\Infrastructure\Queue\Exception\LimitedRetryJobException;
use Integra\Infrastructure\Queue\Job\Integrations\AbstractIntegrationJob;
use Integra\Infrastructure\Queue\Job\Integrations\IntegrationJobInterface;
use Integra\Models\Ubet\User;
use yii\db\Exception;
use yii\helpers\StringHelper;
use yii\queue\Queue;
use Yii;

class AffiseBetJob extends AbstractIntegrationJob implements IntegrationJobInterface
{
    public string $affiseDeviceId;
    public float $betSum;
    public string $receiptId;
    public int $userId;
    public ?int $timeoutSeconds = null;

    public AffiseService $affiseService;

    public function init(): void
    {
        parent::init();
        $this->affiseService = new AffiseService();
    }

    /**
     * @inheritDoc
     */
    public function getQueueComponent(): Queue
    {
        return Yii::$app->queue_triggers_affise;
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
            'affiseDeviceId' => $this->affiseDeviceId,
            'betSum' => $this->betSum,
            'receiptId' => $this->receiptId,
            'userId' => $user->user_id,
            'timeoutSeconds' => $this->timeoutSeconds,
        ];
    }

    /**
     * @inheritDoc
     */
    protected function createHandler(Transport $transport): OperationHandlerInterface
    {
        return new BetHandler(
            $transport
        );
    }

    /**
     * @param array $params
     * @return void
     * @throws Exception
     */
    protected function postSuccess(array $params): void
    {
        $result = $this->getLastResult();

        $this->affiseService->record(
            $result,
            $params['userId'],
            $params
        );
    }

}
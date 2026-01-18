<?php
declare(strict_types=1);

namespace Integra\Infrastructure\Queue\Job\Integrations\Affise;

use Integra\Domain\Integration\Affise\Operation\Register\Handler\RegisterHandler;
use Integra\Domain\Integration\Common\OperationHandlerInterface;
use Integra\Domain\Services\AffiseService;
use Integra\Infrastructure\Environment\Env;
use Integra\Infrastructure\Http\Transport;
use Integra\Infrastructure\Queue\Exception\LimitedRetryJobException;
use Integra\Infrastructure\Queue\Job\Integrations\AbstractIntegrationJob;
use Integra\Infrastructure\Queue\Job\Integrations\IntegrationJobInterface;
use Integra\Models\Ubet\User;
use Yii;
use yii\db\Exception;
use yii\helpers\StringHelper;
use yii\queue\Queue;

final class AffiseRegistrationJob extends AbstractIntegrationJob implements IntegrationJobInterface
{
    public string $affiseDeviceId;
    public int $userId;
    public ?int $timeoutSeconds = null;

    public AffiseService $affiseService;

    public function init(): void
    {
        parent::init();
        $this->affiseService = new AffiseService();
    }

    public function getQueueComponent(): Queue
    {
        return Yii::$app->queue_triggers_affise;
    }

    public function getPushDelaySeconds(): int
    {
        return (int)(new Env('UB_FIRST_TRY_DELAY'))->value();
    }

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
            'affiseDeviceId' => $this->affiseDeviceId,
            'userId' => $user->user_id,
            'timeoutSeconds' => $this->timeoutSeconds,
        ];
    }

    protected function createHandler(Transport $transport): OperationHandlerInterface
    {
        return new RegisterHandler(
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
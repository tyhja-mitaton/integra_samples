<?php
declare(strict_types=1);

namespace Integra\Infrastructure\Queue\Job\Integrations\Adjust;

use Integra\Domain\Integration\Adjust\Operation\Register\Handler\RegisterAdjustHandler;
use Yii;
use yii\queue\Queue;
use yii\helpers\StringHelper;
use Integra\Models\Ubet\User;
use Integra\Infrastructure\Http\Transport;
use Integra\Infrastructure\Environment\Env;
use Integra\Domain\Integration\Common\OperationHandlerInterface;
use Integra\Infrastructure\Queue\Exception\LimitedRetryJobException;
use Integra\Infrastructure\Queue\Job\Integrations\AbstractIntegrationJob;
use Integra\Infrastructure\Queue\Job\Integrations\IntegrationJobInterface;
use Integra\Domain\Integration\Promo\Operation\Register\Handler\RegisterPromoHandler;


final class AdjustRegistrationJob extends AbstractIntegrationJob implements IntegrationJobInterface
{
    public int $userId;
    public ?string $adjustAdid = null;
    public ?string $gpsAdid = null;
    public ?string $idfa = null;
    public ?string $idfv = null;
    public ?int $offerId = null;
    public ?int $partnerId = null;
    public ?int $timeoutSeconds = null;


    public function getQueueComponent(): Queue
    {
        return Yii::$app->queue_triggers_adjust;
    }

    public function getPushDelaySeconds(): int
    {
        return (int)(new Env('UB_FIRST_TRY_DELAY'))->value();
    }

    protected function getRawParams(): array
    {
        //todo как в AlanbaseRegistrationJob
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
            'adjustAdid' => $this->adjustAdid,
            'gpsAdid' => $this->gpsAdid,
            'idfa' => $this->idfa,
            'idfv' => $this->idfv,
            'deviceReg' => $user->device_reg ?? null,
            'partnerId' => $this->partnerId,
            'offerIdId' => $this->offerId,
            'timeoutSeconds' => $this->timeoutSeconds,
        ];
    }

    protected function createHandler(Transport $transport): OperationHandlerInterface
    {
        return new RegisterAdjustHandler($transport);
    }
}
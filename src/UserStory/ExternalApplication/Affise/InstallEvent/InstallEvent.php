<?php

declare(strict_types=1);

namespace Integra\UserStory\ExternalApplication\Affise\InstallEvent;

use Integra\Infrastructure\Generic\Response;
use Integra\Infrastructure\Generic\Response\BadRequest;
use Integra\Infrastructure\Generic\Response\Ok;
use Integra\Infrastructure\Http\Transport;
use Integra\Infrastructure\UserStory;
use Integra\Infrastructure\Validation\Validatable;
use Integra\UserStory\ExternalApplication\Affise\InstallEvent\Request\InstallEventRequest;
use Integra\UserStory\ExternalApplication\Affise\QueryLogTrait;
use Integra\UserStory\ExternalApplication\Affise\ResponseErrorTrait;
use Yii;
use yii\db\Exception;

/**
 * Class InstallEvent
 *
 * @package Integra\UserStory\ExternalApplication\Affise\InstallEvent
 */
class InstallEvent implements UserStory
{
    use ResponseErrorTrait, QueryLogTrait;

    /**
     * @param Validatable $validatable
     * @param Transport $transport
     */
    public function __construct(private Validatable $validatable, private Transport $transport) {}

    /**
     * @return Response
     * @throws Exception
     */
    public function response(): Response
    {
        $validationResult = $this->validatable->result();

        if (!$validationResult->isSuccessful()) {
            return new BadRequest($validationResult->error(), []);
        }

        $validationValue = $validationResult->value();

        $eventInstallResult = $this->transport->response(
            new InstallEventRequest(
                $validationValue['gaid'],
                $validationValue['affise_device_id'],
                $validationValue['random_user_id']
            )
        );

        if (!$eventInstallResult->isSuccessful()) {
            Yii::error($eventInstallResult->error());

            $this->createQueryLog(
                'affise',
                'install',
                true,
                $this->getErrorText($eventInstallResult->error()['body'])
            );

            return new BadRequest(['error' => $this->getErrorText($eventInstallResult->error()['body'])], []);
        }

        $requestId = $eventInstallResult->value()['request_id'] ?? null;

        if (!$requestId) {
            $this->createQueryLog(
                'affise',
                'install',
                true,
                'Не получен request_id'
            );

            return new BadRequest(['Не получен request_id'], []);
        }


        $this->createQueryLog(
            'affise',
            'install'
        );

        return
            new Ok([
                'request_id' => $requestId
            ]);
    }
}
